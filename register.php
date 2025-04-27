<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "connect.php";
include 'Includes/functions/functions.php';

$error_message = '';
$success_message = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['customer_name'];
    $email = $_POST['customer_email'];
    $password = $_POST['customer_password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['customer_phone'];
    $address = $_POST['customer_address'];
    $city = $_POST['customer_city'];
    $zipcode = $_POST['customer_zipcode'];

    // Validate form data
    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address) || empty($city) || empty($zipcode)) {
        $error_message = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long!";
    } else {
        // Check if email already exists
        $check_email = $con->prepare("SELECT client_id FROM clients WHERE client_email = ?");
        $check_email->execute([$email]);
        
        if ($check_email->rowCount() > 0) {
            $error_message = "Email already registered!";
        } else {
            // Insert new client
            $hashedpass = sha1($password);
            $stmt = $con->prepare("INSERT INTO clients (client_name, client_email, client_password, client_phone, client_address, client_city, client_zipcode) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            try {
                $stmt->execute([$name, $email, $hashedpass, $phone, $address, $city, $zipcode]);
                $success_message = "Registration successful! Redirecting to login page...";
                header("refresh:2;url=login.php");
            } catch (PDOException $e) {
                $error_message = "Registration failed. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Amma Chethi Vanta</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #8B4513;
            --secondary-color: #D2691E;
            --accent-color: #FFA07A;
            --text-color: #333;
            --light-bg: #FFF8DC;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('Design/images/indian-food-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            backdrop-filter: blur(10px);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid var(--primary-color);
            padding: 5px;
            background: white;
        }

        .logo-container h1 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            margin-top: 15px;
            font-weight: 700;
        }

        .form-label {
            color: var(--text-color);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
        }

        .input-group-text {
            background: var(--primary-color);
            border: none;
            color: white;
        }

        .btn-register {
            background: var(--primary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            color: var(--text-color);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .password-requirements {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .password-requirements i {
            color: var(--primary-color);
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo-container">
            <img src="Design/images/restaurant-logo1.jpg" alt="Amma Chethi Vanta Logo">
            <h1>Create Account</h1>
        </div>

        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required placeholder="Enter your full name">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="customer_email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" required placeholder="Enter your email">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="customer_password" name="customer_password" required placeholder="Create a password">
                    </div>
                    <div class="password-requirements">
                        <i class="fas fa-info-circle"></i> Minimum 6 characters
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="customer_phone" class="form-label">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required placeholder="Enter your phone number">
                </div>
            </div>

            <div class="mb-3">
                <label for="customer_address" class="form-label">Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    <input type="text" class="form-control" id="customer_address" name="customer_address" required placeholder="Enter your address">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_city" class="form-label">City</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                        <input type="text" class="form-control" id="customer_city" name="customer_city" required placeholder="Enter your city">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="customer_zipcode" class="form-label">Zip Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                        <input type="text" class="form-control" id="customer_zipcode" name="customer_zipcode" required placeholder="Enter your zip code">
                    </div>
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">I agree to the <a href="#" style="color: var(--primary-color);">Terms and Conditions</a></label>
            </div>

            <button type="submit" class="btn btn-primary btn-register w-100">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login Now</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('customer_password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>