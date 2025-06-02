<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Please login to place an order');
    }

    // Validate product_id
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        throw new Exception('Invalid product selection');
    }

    $productId = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $userId = $_SESSION['user_id'];

    // Get database connection
    $pdo = getDBConnection();

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Get product price
        $stmt = $pdo->prepare("SELECT Price FROM Products WHERE ProductID = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception('Product not found');
        }

        // Insert order
        $stmt = $pdo->prepare("
            INSERT INTO Orders (user_id, total, order_date) 
            VALUES (?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$userId, $product['Price']]);

        // Commit transaction
        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully!'
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>


