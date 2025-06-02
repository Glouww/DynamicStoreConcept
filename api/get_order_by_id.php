<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@admin.com') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Order ID is required']);
    exit();
}

$order_id = $_GET['order_id'];

try {
    require_once '../config/database.php';
    $pdo = getDBConnection();

    // Get order details with user information
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.order_date, o.total, 
               u.name as user_name, u.email as user_email, u.phone as user_phone
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.order_id = ?
    ");
    
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit();
    }

    echo json_encode($order);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 