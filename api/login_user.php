<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Validate session and CAPTCHA
    if (!isset($_SESSION['captcha_text'])) {
        throw new Exception('Invalid session');
    }

    // Get and sanitize inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $captchaInput = trim($_POST['captcha']);

    // Validate inputs
    if (empty($email) || empty($password) || empty($captchaInput)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Verify CAPTCHA
    if (strtolower($captchaInput) !== strtolower($_SESSION['captcha_text'])) {
        throw new Exception('Invalid CAPTCHA');
    }

    // Get database connection
    $pdo = getDBConnection();

    // Get user from database - updated to use correct column names
    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('Invalid email or password');
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Invalid email or password');
    }

    // Set session variables - updated to use correct column names
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $email;

    // Clear CAPTCHA from session
    unset($_SESSION['captcha_text']);

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'name' => $user['name']
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 