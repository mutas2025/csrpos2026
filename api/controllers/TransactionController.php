<?php
// TransactionController.php
// Handles transaction history, viewing, and receipt reprinting

require_once dirname(__DIR__) . '/config/db_connect.php';

class TransactionController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Get all transactions with pagination
     */
    public function getTransactions($page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            // Get total count
            $countStmt = $this->con->query("SELECT COUNT(*) as total FROM pos_orders");
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get paginated transactions
            $stmt = $this->con->prepare("
                SELECT 
                    o.objid as order_id,
                    o.customer_id,
                    COALESCE(c.fullname, 'Walk-in Customer') as customer_name,
                    o.total_amount,
                    o.tax_amount,
                    o.discount_amount,
                    o.net_amount,
                    o.payment_method,
                    o.amount_tendered,
                    o.change_amount,
                    o.status,
                    o.date_created
                FROM pos_orders o
                LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                ORDER BY o.date_created DESC
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'data' => [
                    'transactions' => $transactions,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => (int)$totalCount,
                        'total_pages' => ceil($totalCount / $limit)
                    ]
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get detailed information for a specific transaction
     */
    public function getTransactionDetails($orderId) {
        try {
            // Get order information
            $orderStmt = $this->con->prepare("
                SELECT 
                    o.objid as order_id,
                    o.customer_id,
                    COALESCE(c.fullname, 'Walk-in Customer') as customer_name,
                    COALESCE(c.email, 'N/A') as customer_email,
                    COALESCE(c.phone, 'N/A') as customer_phone,
                    o.total_amount,
                    o.tax_amount,
                    o.discount_amount,
                    o.net_amount,
                    o.payment_method,
                    o.amount_tendered,
                    o.change_amount,
                    o.status,
                    o.date_created
                FROM pos_orders o
                LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                WHERE o.objid = :order_id
            ");
            
            $orderStmt->execute([':order_id' => $orderId]);
            $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                return ['status' => 'error', 'message' => 'Transaction not found.'];
            }
            
            // Get order items
            $itemsStmt = $this->con->prepare("
                SELECT 
                    product_id,
                    product_name,
                    price,
                    quantity,
                    subtotal
                FROM pos_order_items
                WHERE order_id = :order_id
            ");
            
            $itemsStmt->execute([':order_id' => $orderId]);
            $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'data' => [
                    'order' => $order,
                    'items' => $items
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Reprint receipt for a specific transaction
     */
    public function reprintReceipt($orderId) {
        try {
            // Get order information
            $orderStmt = $this->con->prepare("
                SELECT 
                    o.objid as order_id,
                    o.customer_id,
                    COALESCE(c.fullname, 'Walk-in Customer') as customer_name,
                    COALESCE(c.email, 'N/A') as customer_email,
                    COALESCE(c.phone, 'N/A') as customer_phone,
                    o.total_amount,
                    o.tax_amount,
                    o.discount_amount,
                    o.net_amount,
                    o.payment_method,
                    o.amount_tendered,
                    o.change_amount,
                    o.status,
                    o.date_created
                FROM pos_orders o
                LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                WHERE o.objid = :order_id
            ");
            
            $orderStmt->execute([':order_id' => $orderId]);
            $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                return ['status' => 'error', 'message' => 'Transaction not found.'];
            }
            
            // Get order items
            $itemsStmt = $this->con->prepare("
                SELECT 
                    product_name,
                    price,
                    quantity,
                    subtotal
                FROM pos_order_items
                WHERE order_id = :order_id
            ");
            
            $itemsStmt->execute([':order_id' => $orderId]);
            $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Prepare receipt data
            $receiptData = [
                'order_id' => $order['order_id'],
                'customer' => $order['customer_name'],
                'customer_email' => $order['customer_email'],
                'customer_phone' => $order['customer_phone'],
                'date' => date('Y-m-d H:i:s', strtotime($order['date_created'])),
                'items' => $items,
                'subtotal' => number_format($order['total_amount'], 2),
                'tax' => number_format($order['tax_amount'], 2),
                'discount' => number_format($order['discount_amount'], 2),
                'total' => number_format($order['net_amount'], 2),
                'payment_method' => $order['payment_method'],
                'tendered' => number_format($order['amount_tendered'], 2),
                'change' => number_format($order['change_amount'], 2),
                'status' => $order['status']
            ];
            
            return [
                'status' => 'success',
                'message' => 'Receipt retrieved successfully.',
                'receipt' => $receiptData
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get transaction count (for dashboard stats)
     */
    public function getTransactionCount() {
        try {
            // Get today's transactions count
            $todayStmt = $this->con->prepare("
                SELECT COUNT(*) as count, COALESCE(SUM(net_amount), 0) as total 
                FROM pos_orders 
                WHERE DATE(date_created) = CURDATE() AND status = 'COMPLETED'
            ");
            $todayStmt->execute();
            $today = $todayStmt->fetch(PDO::FETCH_ASSOC);
            
            // Get current month transactions count
            $monthStmt = $this->con->prepare("
                SELECT COUNT(*) as count, COALESCE(SUM(net_amount), 0) as total 
                FROM pos_orders 
                WHERE MONTH(date_created) = MONTH(CURDATE()) 
                AND YEAR(date_created) = YEAR(CURDATE())
                AND status = 'COMPLETED'
            ");
            $monthStmt->execute();
            $month = $monthStmt->fetch(PDO::FETCH_ASSOC);
            
            // Get total transactions count
            $totalStmt = $this->con->prepare("
                SELECT COUNT(*) as count, COALESCE(SUM(net_amount), 0) as total 
                FROM pos_orders 
                WHERE status = 'COMPLETED'
            ");
            $totalStmt->execute();
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'data' => [
                    'today' => [
                        'count' => (int)$today['count'],
                        'total' => floatval($today['total'])
                    ],
                    'this_month' => [
                        'count' => (int)$month['count'],
                        'total' => floatval($month['total'])
                    ],
                    'total_all' => [
                        'count' => (int)$total['count'],
                        'total' => floatval($total['total'])
                    ]
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Search transactions by customer name or order ID
     */
    public function searchTransactions($keyword) {
        try {
            $stmt = $this->con->prepare("
                SELECT 
                    o.objid as order_id,
                    o.customer_id,
                    COALESCE(c.fullname, 'Walk-in Customer') as customer_name,
                    o.total_amount,
                    o.tax_amount,
                    o.discount_amount,
                    o.net_amount,
                    o.payment_method,
                    o.amount_tendered,
                    o.change_amount,
                    o.status,
                    o.date_created
                FROM pos_orders o
                LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                WHERE CAST(o.objid AS CHAR) LIKE :keyword 
                OR c.fullname LIKE :keyword
                ORDER BY o.date_created DESC
                LIMIT 50
            ");
            
            $searchTerm = "%{$keyword}%";
            $stmt->bindParam(':keyword', $searchTerm);
            $stmt->execute();
            
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'data' => [
                    'transactions' => $transactions,
                    'search_keyword' => $keyword,
                    'count' => count($transactions)
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>