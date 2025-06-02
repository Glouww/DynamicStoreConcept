<?php
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';
$dbname = 'mystore';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get category from query parameter
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    if (empty($category)) {
        throw new Exception('Category parameter is required');
    }

    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT ProductID, ProductName, Price FROM products WHERE Category = ?");
    $stmt->execute([$category]);
    
    // Fetch all products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode($products);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?> 