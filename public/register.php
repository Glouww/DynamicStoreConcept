<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Grocery Store</title>
    <!-- Inline CSS styles for the registration page -->
    <style>
        /* Main container styling */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
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
        /* Error message styling */
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
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
        }
        .submit-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        /* Form title styling */
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Main container for the registration form -->
    <div class="container">
        <div id="registration-form"></div>
    </div>

    <!-- React and Babel CDN imports -->
    <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

    <!-- React component for registration form -->
    <script type="text/babel">
        /**
         * RegistrationForm Component
         * Handles user registration with form validation and submission
         */
        function RegistrationForm() {
            // State management for form data
            const [formData, setFormData] = React.useState({
                name: '',
                phone: '',
                email: '',
                password: ''
            });

            // State management for form validation errors
            const [errors, setErrors] = React.useState({
                name: '',
                phone: '',
                email: '',
                password: ''
            });

            // State to track form submission status
            const [isSubmitting, setIsSubmitting] = React.useState(false);

            /**
             * Validation functions for each form field
             * Returns error message if validation fails, empty string if valid
             */
            const validateName = (name) => {
                if (!name) return 'Name is required';
                if (!/^[A-Za-z\s]+$/.test(name)) return 'Name can only contain letters and spaces';
                return '';
            };

            const validatePhone = (phone) => {
                if (!phone) return 'Phone number is required';
                if (!/^\d{10}$/.test(phone)) return 'Phone number must be 10 digits';
                return '';
            };

            const validateEmail = (email) => {
                if (!email) return 'Email is required';
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return 'Invalid email format';
                return '';
            };

            const validatePassword = (password) => {
                if (!password) return 'Password is required';
                if (password.length < 6) return 'Password must be at least 6 characters';
                return '';
            };

            /**
             * Handles input changes and validates the changed field
             * Updates form data and error states
             */
            const handleChange = (e) => {
                const { name, value } = e.target;
                setFormData(prev => ({
                    ...prev,
                    [name]: value
                }));

                // Validate the changed field
                let error = '';
                switch (name) {
                    case 'name':
                        error = validateName(value);
                        break;
                    case 'phone':
                        error = validatePhone(value);
                        break;
                    case 'email':
                        error = validateEmail(value);
                        break;
                    case 'password':
                        error = validatePassword(value);
                        break;
                }

                setErrors(prev => ({
                    ...prev,
                    [name]: error
                }));
            };

            /**
             * Handles form submission
             * Validates all fields and sends data to server
             */
            const handleSubmit = async (e) => {
                e.preventDefault();
                
                // Validate all fields
                const newErrors = {
                    name: validateName(formData.name),
                    phone: validatePhone(formData.phone),
                    email: validateEmail(formData.email),
                    password: validatePassword(formData.password)
                };

                setErrors(newErrors);

                // Check if there are any errors
                if (Object.values(newErrors).some(error => error !== '')) {
                    return;
                }

                setIsSubmitting(true);

                try {
                    // Send registration request to server
                    const response = await fetch('../api/register_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData)
                    });

                    if (response.ok) {
                        const data = await response.json();
                        // Handle successful registration
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = 'login.php';
                        }
                    } else {
                        // Handle registration failure
                        const data = await response.json();
                        alert(data.message || 'Registration failed. Please try again.');
                    }
                } catch (error) {
                    // Handle network or server errors
                    alert('An error occurred. Please try again.');
                } finally {
                    setIsSubmitting(false);
                }
            };

            // Render the registration form
            return (
                <div>
                    <h2 className="form-title">Create an Account</h2>
                    <form onSubmit={handleSubmit}>
                        {/* Name input field */}
                        <div className="form-group">
                            <label htmlFor="name">Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value={formData.name}
                                onChange={handleChange}
                                placeholder="Enter your name"
                            />
                            {errors.name && <div className="error-message">{errors.name}</div>}
                        </div>

                        {/* Phone number input field */}
                        <div className="form-group">
                            <label htmlFor="phone">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value={formData.phone}
                                onChange={handleChange}
                                placeholder="Enter your phone number"
                            />
                            {errors.phone && <div className="error-message">{errors.phone}</div>}
                        </div>

                        {/* Email input field */}
                        <div className="form-group">
                            <label htmlFor="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value={formData.email}
                                onChange={handleChange}
                                placeholder="Enter your email"
                            />
                            {errors.email && <div className="error-message">{errors.email}</div>}
                        </div>

                        {/* Password input field */}
                        <div className="form-group">
                            <label htmlFor="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                value={formData.password}
                                onChange={handleChange}
                                placeholder="Enter your password"
                            />
                            {errors.password && <div className="error-message">{errors.password}</div>}
                        </div>

                        {/* Submit button */}
                        <button 
                            type="submit" 
                            className="submit-button"
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? 'Registering...' : 'Register'}
                        </button>
                    </form>
                </div>
            );
        }

        // Render the RegistrationForm component
        ReactDOM.render(<RegistrationForm />, document.getElementById('registration-form'));
    </script>
</body>
</html> 