<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if user is already logged in
if (isset($_SESSION['client_id'])) {
    header("Location: order_food.php");
    exit();
}

// Handle login form submission
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "connect.php";
    include 'Includes/functions/functions.php';
    
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];
    $hashedpass = sha1($password);

    $stmt = $con->prepare("SELECT * FROM clients WHERE client_email = ? AND client_password = ?");
    $stmt->execute([$email, $hashedpass]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['client_id'] = $user['client_id'];
        header("Location: order_food.php");
        exit();
    } else {
        $error_message = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Amma Chethi Vanta</title>
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

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 120px;
            height: 120px;
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

        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: var(--text-color);
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .social-login {
            text-align: center;
            margin-top: 25px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            transition: all 0.3s ease;
            color: white;
        }

        .social-btn.facebook { background: #3b5998; }
        .social-btn.google { background: #db4437; }
        .social-btn.twitter { background: #1da1f2; }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e1e1e1;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            z-index: 1;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="Design/images/restaurant-logo1.jpg" alt="Amma Chethi Vanta Logo">
            <h1>Welcome Back!</h1>
        </div>

        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="user_email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="user_email" name="user_email" required placeholder="Enter your email">
                </div>
            </div>

            <div class="mb-3">
                <label for="user_password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="user_password" name="user_password" required placeholder="Enter your password">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>
                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">Login</button>
        </form>


        <div class="register-link">
            Don't have an account? <a href="register.php">Register Now</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>