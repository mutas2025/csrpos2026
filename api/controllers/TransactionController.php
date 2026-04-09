<?php
// TransactionController.php
// Handles retrieving sales history and transaction details

require_once dirname(__DIR__) . '/config/db_connect.php';

class TransactionController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Get all transactions (Order Headers)
     */
    public function getTransactions() {
        try {
            // Join with customers to show name instead of ID
            // Selecting only necessary columns to ensure clean data
            $sql = "SELECT 
                        o.objid, 
                        o.order_id, 
                        COALESCE(c.fullname, 'Walk-in Customer') as customer_name, 
                        o.total_amount, 
                        o.payment_method, 
                        o.status, 
                        o.date_created 
                    FROM pos_orders o
                    LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                    ORDER BY o.date_created DESC";
            
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['status' => 'success', 'data' => $transactions];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get details of a specific transaction (Receipt view)
     */
    public function getTransactionDetails($id) {
        try {
            // Get Order Header
            $sql = "SELECT 
                        o.*, 
                        COALESCE(c.fullname, 'Walk-in Customer') as customer_name 
                    FROM pos_orders o
                    LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                    WHERE o.objid = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return ['status' => 'error', 'message' => 'Transaction not found.'];
            }

            // Get Order Items
            // FIX: Changed 'qty' to 'quantity' to match the database schema
            $itemSql = "SELECT objid, product_name, price, quantity, subtotal FROM pos_order_items WHERE order_id = ?";
            $itemStmt = $this->con->prepare($itemSql);
            $itemStmt->execute([$id]);
            $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

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
}
?>