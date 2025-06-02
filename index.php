<?php
// index.php - basic router
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'register':
        require 'public/register.php';
        break;
    case 'login':
        require 'public/login.php';
        break;
    case 'order':
        require 'public/order.php';
        break;
    default:
        require 'public/home.php'; // for product dropdowns
}

?>