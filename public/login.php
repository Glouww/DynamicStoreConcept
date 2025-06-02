<?php
// Start the session to maintain user state across pages
session_start();

// CAPTCHA Image Selection Logic
// Get all CAPTCHA images from the assets directory
$captchaFolder = '../assets/captcha/';
$captchaImages = glob($captchaFolder . '*.jpg');

// Check if CAPTCHA images exist
if (empty($captchaImages)) {
    die('Error: No CAPTCHA images found in ' . $captchaFolder);
}

// Select a random CAPTCHA image and store its text in the session
$randomImage = $captchaImages[array_rand($captchaImages)];
$captchaText = pathinfo($randomImage, PATHINFO_FILENAME);
$_SESSION['captcha_text'] = $captchaText;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grocery Store</title>
    <!-- Inline CSS styles for the login page -->
    <style>
        /* Main container styling */
        .container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        /* Form group styling */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        /* CAPTCHA container styling */
        .captcha-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .captcha-image {
            max-width: 150px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        /* Submit button styling */
        .submit-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
        /* Error message styling */
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            display: none;
        }
    </style>
</head>
<body>
    <!-- Main login container -->
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 30px;">Login</h2>
        
        <!-- Error message display area -->
        <div id="error-message" class="error-message"></div>

        <!-- Login form -->
        <form id="login-form" action="/mystore/api/login_user.php" method="POST">
            <!-- Email input field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Password input field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- CAPTCHA verification section -->
            <div class="captcha-container">
                <img src="<?php echo $randomImage; ?>" alt="CAPTCHA" class="captcha-image">
                <div class="form-group" style="flex-grow: 1;">
                    <label for="captcha">Enter the text shown</label>
                    <input type="text" id="captcha" name="captcha" required>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="submit-button">Login</button>
        </form>
    </div>

    <!-- JavaScript for form submission handling -->
    <script>
        // Add event listener for form submission
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form data and error message element
            const formData = new FormData(this);
            const errorMessage = document.getElementById('error-message');
            
            try {
                // Send login request to the server
                const response = await fetch('/mystore/api/login_user.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Redirect to home page on successful login
                    window.location.href = 'home.php';
                } else {
                    // Display error message if login fails
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                }
            } catch (error) {
                // Handle network or server errors
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html> 