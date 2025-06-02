<?php
// Start the session to maintain user state across pages
session_start();

// Security check: Verify if user is logged in and has admin privileges
// Redirects to home page if user is not admin or not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@admin.com') {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Grocery Store</title>
    <!-- External CSS and font imports -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header section with navigation -->
    <header class="header">
        <div class="container header-content">
            <!-- Logo and store name -->
            <a href="home.php" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Grocery Store
            </a>
            <!-- Authentication section -->
            <div class="auth-buttons">
                <!-- Logout button -->
                <a href="logout.php" class="auth-button logout-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main content section -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome card -->
            <div class="card">
                <h1>Admin Dashboard</h1>
                <p class="text-muted">Welcome to the admin dashboard. Here you can manage orders and view customer information.</p>
            </div>
            
            <!-- Order lookup form -->
            <div class="card search-form">
                <h2>Lookup Order</h2>
                <div class="form-group">
                    <input type="text" id="order-id" class="form-control" placeholder="Enter Order ID">
                    <button onclick="lookupOrder()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        Search
                    </button>
                </div>
            </div>

            <!-- Error message display area -->
            <div id="error-message" class="message error-message" style="display: none;"></div>

            <!-- Order details display section -->
            <div id="order-details" class="card" style="display: none;">
                <h2>Order Details</h2>
                <div class="table-responsive">
                    <table class="table">
                        <!-- Order information rows -->
                        <tr>
                            <th>Order ID</th>
                            <td id="display-order-id"></td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td id="display-order-date"></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td id="display-total"></td>
                        </tr>
                        <!-- Customer information rows -->
                        <tr>
                            <th>User Name</th>
                            <td id="display-user-name"></td>
                        </tr>
                        <tr>
                            <th>User Email</th>
                            <td id="display-user-email"></td>
                        </tr>
                        <tr>
                            <th>User Phone</th>
                            <td id="display-user-phone"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        /**
         * Function to lookup order details by order ID
         * Makes an API call to fetch order information and updates the UI accordingly
         */
        function lookupOrder() {
            const orderId = document.getElementById('order-id').value;
            const orderDetails = document.getElementById('order-details');
            const errorMessage = document.getElementById('error-message');

            // Validate order ID input
            if (!orderId) {
                errorMessage.textContent = 'Please enter an order ID';
                errorMessage.style.display = 'block';
                orderDetails.style.display = 'none';
                return;
            }

            // Fetch order details from the API
            fetch(`../api/get_order_by_id.php?order_id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // Display error message if order not found or other error occurs
                        errorMessage.textContent = data.error;
                        errorMessage.style.display = 'block';
                        orderDetails.style.display = 'none';
                    } else {
                        // Display order details if found
                        errorMessage.style.display = 'none';
                        orderDetails.style.display = 'block';
                        
                        // Update order information in the table
                        document.getElementById('display-order-id').textContent = data.order_id;
                        document.getElementById('display-order-date').textContent = data.order_date;
                        document.getElementById('display-total').textContent = `£${data.total}`;
                        document.getElementById('display-user-name').textContent = data.user_name;
                        document.getElementById('display-user-email').textContent = data.user_email;
                        document.getElementById('display-user-phone').textContent = data.user_phone;
                    }
                })
                .catch(error => {
                    // Handle network or server errors
                    errorMessage.textContent = 'An error occurred while fetching the order details';
                    errorMessage.style.display = 'block';
                    orderDetails.style.display = 'none';
                });
        }
    </script>
</body>
</html> 