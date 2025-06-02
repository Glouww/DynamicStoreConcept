<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_unset();
session_destroy();

// Redirect to home page
header('Location: home.php');
exit();
?> 