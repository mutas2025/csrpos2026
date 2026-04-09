<?php
// PosController.php
// Handles POS logic: Cart processing, Payment, Receipt generation

require_once dirname(__DIR__) . '/config/db_connect.php';

class PosController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Get data required for the POS page (Products and Customers)
     */
    public function getPosData() {
        try {
            // 1. Get Products
            $prodStmt = $this->con->prepare("SELECT objid, product_code, product_name, category, price, stock FROM tbl_products WHERE stock > 0 ORDER BY product_name ASC");
            $prodStmt->execute();
            $products = $prodStmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Get Customers
            $custStmt = $this->con->prepare("SELECT objid, customer_code, fullname, email, phone FROM tbl_customers ORDER BY fullname ASC");
            $custStmt->execute();
            $customers = $custStmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => 'success',
                'data' => [
                    'products' => $products,
                    'customers' => $customers
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Process the Transaction (Checkout)
     * Ensures order_id is generated in pos_orders and strictly used in pos_order_items.
     */
    public function processTransaction(array $data) {
        try {
            // Start Transaction
            $this->con->beginTransaction();

            $customer_id = isset($data['customer_id']) && !empty($data['customer_id']) ? $data['customer_id'] : null;
            $cartItems = $data['cart'] ?? [];
            $discountAmount = isset($data['discount']) ? floatval($data['discount']) : 0;
            $paymentMethod = $data['payment_method'] ?? 'Cash';
            $amountTendered = isset($data['amount_tendered']) ? floatval($data['amount_tendered']) : 0;
            $taxRate = 0.12; // 12% VAT

            if (empty($cartItems)) {
                throw new Exception("Cart is empty.");
            }

            $subtotal = 0;
            $totalTax = 0;

            // 1. Validate Stock and Calculate Totals
            foreach ($cartItems as $item) {
                $pid = $item['objid'];
                $qty = $item['qty'];
                $price = $item['price'];
                
                // Check current DB stock with row locking to prevent race conditions
                $stmt = $this->con->prepare("SELECT stock FROM tbl_products WHERE objid = ? FOR UPDATE");
                $stmt->execute([$pid]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Product not found (ID: $pid).");
                }
                if ($product['stock'] < $qty) {
                    throw new Exception("Insufficient stock for " . $item['product_name'] . ".");
                }

                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;
                
                // Calculate tax
                $totalTax += ($itemSubtotal * $taxRate);
            }

            $netAmount = $subtotal + $totalTax - $discountAmount;

            if ($netAmount < 0) $netAmount = 0;

            // 2. Validate Payment
            if ($paymentMethod === 'Cash') {
                if ($amountTendered < $netAmount) {
                    throw new Exception("Insufficient amount tendered.");
                }
            } else {
                // For non-cash, assume exact payment or handle external logic separately
                $amountTendered = $netAmount; 
            }
            
            $changeAmount = $amountTendered - $netAmount;

            // 3. Insert into pos_orders
            // We assume 'pos_orders' has an auto-increment primary key 'objid' or 'id'.
            // We do not insert order_id manually; we let the Database auto-generate it.
            $orderSql = "INSERT INTO pos_orders 
                         (customer_id, total_amount, tax_amount, discount_amount, net_amount, payment_method, amount_tendered, change_amount, status, date_created)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'COMPLETED', NOW())";
            
            $orderStmt = $this->con->prepare($orderSql);
            $orderStmt->execute([
                $customer_id, 
                $subtotal, 
                $totalTax, 
                $discountAmount, 
                $netAmount, 
                $paymentMethod, 
                $amountTendered, 
                $changeAmount
            ]);

            // 4. Retrieve the Auto-Generated Order ID
            // This is the key step. This ID comes from pos_orders.
            $orderId = $this->con->lastInsertId();

            // 5. Insert Items and Update Stock
            // We use the $orderId retrieved above to ensure pos_order_items references the correct order.
            $itemSql = "INSERT INTO pos_order_items (order_id, product_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $this->con->prepare($itemSql);

            $updateStockSql = "UPDATE tbl_products SET stock = stock - ? WHERE objid = ?";
            $updateStockStmt = $this->con->prepare($updateStockSql);

            foreach ($cartItems as $item) {
                $itemSub = $item['price'] * $item['qty'];
                
                // Insert into pos_order_items using the SAME $orderId
                $itemStmt->execute([
                    $orderId,      // This links the item to the order created in step 3
                    $item['objid'],
                    $item['product_name'],
                    $item['price'],
                    $item['qty'],
                    $itemSub
                ]);

                // Update Product Stock
                $updateStockStmt->execute([$item['qty'], $item['objid']]);
            }

            // Commit Transaction
            $this->con->commit();

            // 6. Prepare Receipt Data
            // Fetch customer name if exists
            $customerName = "Walk-in Customer";
            if ($customer_id) {
                $cStmt = $this->con->prepare("SELECT fullname FROM tbl_customers WHERE objid = ?");
                $cStmt->execute([$customer_id]);
                $cData = $cStmt->fetch(PDO::FETCH_ASSOC);
                if($cData) $customerName = $cData['fullname'];
            }

            $receiptData = [
                'order_id' => $orderId,
                'customer' => $customerName,
                'date' => date('Y-m-d H:i:s'),
                'items' => $cartItems,
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($totalTax, 2),
                'discount' => number_format($discountAmount, 2),
                'total' => number_format($netAmount, 2),
                'payment_method' => $paymentMethod,
                'tendered' => number_format($amountTendered, 2),
                'change' => number_format($changeAmount, 2)
            ];

            return [
                'status' => 'success',
                'message' => 'Transaction completed successfully.',
                'receipt' => $receiptData
            ];

        } catch (Exception $e) {
            // Rollback if any error occurs
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
?>