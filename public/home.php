<?php
// Start the session to maintain user state across pages
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Grocery Store</title>
    <!-- External CSS and font imports -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header section with navigation and authentication -->
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
            <!-- Authentication buttons section -->
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Display welcome message for logged-in users -->
                    <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <?php if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@admin.com'): ?>
                        <!-- Admin dashboard link (only visible to admin users) -->
                        <a href="admin.php" class="auth-button" style="background-color: var(--gray-600); color: var(--white);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>
                            Dashboard
                        </a>
                    <?php endif; ?>
                    <!-- Logout button for logged-in users -->
                    <a href="logout.php" class="auth-button logout-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                <?php else: ?>
                    <!-- Login and Register buttons for guests -->
                    <a href="login.php" class="auth-button login-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        Login
                    </a>
                    <a href="register.php" class="auth-button register-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main content section -->
    <main class="main-content">
        <div class="container">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Personalized welcome message for logged-in users -->
                <div class="card welcome-section">
                    <h1>Welcome to Our Grocery Store!</h1>
                    <p>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! We're glad to have you here.</p>
                </div>
            <?php else: ?>
                <!-- Generic welcome message for guests -->
                <div class="card welcome-section">
                    <h1>Welcome to Our Grocery Store!</h1>
                    <p>Please login or register to start shopping.</p>
                </div>
            <?php endif; ?>
            
            <!-- Product selection card -->
            <div class="card">
                <!-- Category and product dropdowns -->
                <div class="dropdown-container">
                    <select id="category-select" class="form-control">
                        <option value="">Select a category</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Meat">Meat</option>
                    </select>

                    <select id="product-select" class="form-control" disabled>
                        <option value="">Select a product</option>
                    </select>
                </div>

                <!-- Product details display section -->
                <div id="product-display" class="product-display" style="display: none;">
                    <h3>Selected Product Details</h3>
                    <img id="product-image" class="product-image" src="" alt="Product Image" style="display: none;">
                    <div class="product-details">
                        <p>Name: <span id="product-name"></span></p>
                        <p>Price: £<span id="product-price"></span></p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Order button for logged-in users -->
                            <div class="order-button-container">
                                <button id="order-button" class="btn btn-primary" style="display: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    Order Now
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Login prompt for guests -->
                            <div class="login-prompt">
                                Please <a href="login.php">log in</a> to place an order.
                            </div>
                        <?php endif; ?>
                        <!-- Order status message display -->
                        <div id="order-message" class="message"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- External JavaScript imports -->
    <script src="../assets/js/dropdown.js"></script>
    <script>
        // Event listener for product selection changes
        document.getElementById('product-select').addEventListener('change', function() {
            const productDisplay = document.getElementById('product-display');
            const orderButton = document.getElementById('order-button');
            const orderMessage = document.getElementById('order-message');
            
            if (this.value) {
                // Update product details when a product is selected
                const selectedOption = this.options[this.selectedIndex];
                const productText = selectedOption.textContent;
                
                document.getElementById('product-name').textContent = productText;
                document.getElementById('product-price').textContent = this.value;
                
                // Show product display and order button
                productDisplay.style.display = 'block';
                if (orderButton) {
                    orderButton.style.display = 'inline-flex';
                    orderButton.disabled = false;
                }
                orderMessage.style.display = 'none';
            } else {
                // Hide product display when no product is selected
                productDisplay.style.display = 'none';
                if (orderButton) {
                    orderButton.style.display = 'none';
                }
                orderMessage.style.display = 'none';
            }
        });

        // Handle order placement functionality
        const orderButton = document.getElementById('order-button');
        if (orderButton) {
            orderButton.addEventListener('click', function() {
                const productId = document.getElementById('product-select').value;
                const orderMessage = document.getElementById('order-message');
                
                // Send order request to the server
                fetch('../api/place_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    // Display order status message
                    orderMessage.textContent = data.message;
                    orderMessage.className = `message ${data.success ? 'success-message' : 'error-message'}`;
                    orderMessage.style.display = 'block';
                    
                    // Disable order button after successful order
                    if (data.success) {
                        orderButton.disabled = true;
                        orderButton.style.backgroundColor = 'var(--gray-500)';
                    }
                })
                .catch(error => {
                    // Handle error cases
                    console.error('Error placing order:', error);
                    orderMessage.textContent = 'Error placing order. Please try again.';
                    orderMessage.className = 'message error-message';
                    orderMessage.style.display = 'block';
                });
            });
        }
    </script>
</body>
</html>
