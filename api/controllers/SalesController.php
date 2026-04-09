<?php
// SalesController.php
// Handles sales analytics and reporting

require_once dirname(__DIR__) . '/config/db_connect.php';

class SalesController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Get Sales Report based on date range
     */
    public function getSalesReport($startDate, $endDate) {
        try {
            // Default to today if no dates provided
            if (!$startDate) $startDate = date('Y-m-d');
            if (!$endDate) $endDate = date('Y-m-d');

            // Adjust end date to include the full day
            $endDate .= ' 23:59:59';

            // 1. Get Aggregate Data
            $sql = "SELECT 
                        COUNT(*) as total_transactions,
                        SUM(net_amount) as total_revenue,
                        SUM(discount_amount) as total_discount,
                        SUM(tax_amount) as total_tax
                    FROM pos_orders 
                    WHERE status = 'COMPLETED' 
                    AND date_created BETWEEN ? AND ?";
            
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            $summary = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Get Detailed Sales List
            // REMOVED 'o.order_id' from SELECT as requested. Using 'o.objid' only.
            $listSql = "SELECT 
                            o.objid,
                            COALESCE(c.fullname, 'Walk-in Customer') as customer_name,
                            o.net_amount,
                            o.payment_method,
                            o.date_created
                        FROM pos_orders o
                        LEFT JOIN tbl_customers c ON o.customer_id = c.objid
                        WHERE o.status = 'COMPLETED' 
                        AND o.date_created BETWEEN ? AND ?
                        ORDER BY o.date_created DESC";
            
            $listStmt = $this->con->prepare($listSql);
            $listStmt->execute([$startDate, $endDate]);
            $sales = $listStmt->fetchAll(PDO::FETCH_ASSOC);

            // 3. Get Sales by Product (Top Sellers)
            $prodSql = "SELECT 
                            product_name, 
                            SUM(quantity) as total_qty, 
                            SUM(subtotal) as total_sales 
                        FROM pos_order_items
                        JOIN pos_orders ON pos_order_items.order_id = pos_orders.objid
                        WHERE pos_orders.status = 'COMPLETED'
                        AND pos_orders.date_created BETWEEN ? AND ?
                        GROUP BY product_name 
                        ORDER BY total_sales DESC";
            
            $prodStmt = $this->con->prepare($prodSql);
            $prodStmt->execute([$startDate, $endDate]);
            $topProducts = $prodStmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => 'success',
                'summary' => $summary,
                'sales' => $sales,
                'top_products' => $topProducts,
                'filters' => [
                    'start' => $startDate,
                    'end' => substr($endDate, 0, 10)
                ]
            ];

        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>