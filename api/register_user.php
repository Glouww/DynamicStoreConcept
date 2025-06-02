<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Get JSON data from request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validate required fields
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password']) || !isset($data['phone'])) {
        throw new Exception('All fields are required');
    }

    // Sanitize inputs
    $name = htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($data['phone']), ENT_QUOTES, 'UTF-8');
    $password = $data['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Validate name (letters and spaces only)
    if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        throw new Exception('Name can only contain letters and spaces');
    }

    // Validate phone (10 digits)
    if (!preg_match('/^\d{10}$/', $phone)) {
        throw new Exception('Phone number must be 10 digits');
    }

    // Validate password length
    if (strlen($password) < 6) {
        throw new Exception('Password must be at least 6 characters');
    }

    // Get database connection
    $pdo = getDBConnection();

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Email already registered');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $email,
        $hashedPassword,
        $phone
    ]);

    // Return success response with correct redirect URL
    echo json_encode([
        'success' => true, 
        'message' => 'Registration successful',
        'redirect' => '/mystore/public/login.php'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 