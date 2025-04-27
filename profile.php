<?php
// Start the session at the beginning of the script
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['client_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Include database connection
require_once './connect.php';

// Get client information
$client_id = $_SESSION['client_id'];
$query = "SELECT * FROM clients WHERE client_id = ?";
$stmt = $con->prepare($query);
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
$update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $name = htmlspecialchars(trim($_POST['client_name']));
    $phone = htmlspecialchars(trim($_POST['client_phone']));
    $email = htmlspecialchars(trim($_POST['client_email']));
    $address = htmlspecialchars(trim($_POST['client_address']));
    $city = htmlspecialchars(trim($_POST['client_city']));
    $zipcode = (int)$_POST['client_zipcode'];
    
    // Update client information
    $update_query = "UPDATE clients SET 
                    client_name = ?, 
                    client_phone = ?, 
                    client_email = ?, 
                    client_address = ?, 
                    client_city = ?, 
                    client_zipcode = ? 
                    WHERE client_id = ?";
    
    $update_stmt = $con->prepare($update_query);
    
    if ($update_stmt->execute([$name, $phone, $email, $address, $city, $zipcode, $client_id])) {
        $update_message = '<div class="alert alert-success">Profile updated successfully!</div>';
        
        // Update the client data for display
        $client['client_name'] = $name;
        $client['client_phone'] = $phone;
        $client['client_email'] = $email;
        $client['client_address'] = $address;
        $client['client_city'] = $city;
        $client['client_zipcode'] = $zipcode;
    } else {
        $update_message = '<div class="alert alert-danger">Error updating profile</div>';
    }
}

// Handle password change
$password_message = '';
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $hashed_current = sha1($current_password);
    $check_query = "SELECT client_password FROM clients WHERE client_id = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->execute([$client_id]);
    $stored_password = $check_stmt->fetch(PDO::FETCH_ASSOC)['client_password'];
    
    if ($hashed_current === $stored_password) {
        // Check if new passwords match
        if ($new_password === $confirm_password) {
            // Update password
            $hashed_new = sha1($new_password);
            $password_query = "UPDATE clients SET client_password = ? WHERE client_id = ?";
            $password_stmt = $con->prepare($password_query);
            
            if ($password_stmt->execute([$hashed_new, $client_id])) {
                $password_message = '<div class="alert alert-success">Password updated successfully!</div>';
            } else {
                $password_message = '<div class="alert alert-danger">Error updating password</div>';
            }
        } else {
            $password_message = '<div class="alert alert-danger">New passwords do not match!</div>';
        }
    } else {
        $password_message = '<div class="alert alert-danger">Current password is incorrect!</div>';
    }
}

// Get order history
$order_query = "SELECT po.order_id, po.order_time, po.delivery_address, po.delivered, po.canceled, 
                COUNT(io.menu_id) as item_count, 
                SUM(m.menu_price * io.quantity) as total_price 
                FROM placed_orders po 
                JOIN in_order io ON po.order_id = io.order_id 
                JOIN menus m ON io.menu_id = m.menu_id 
                WHERE po.client_id = ? 
                GROUP BY po.order_id 
                ORDER BY po.order_time DESC 
                LIMIT 5";
$order_stmt = $con->prepare($order_query);
$order_stmt->execute([$client_id]);
$recent_orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "My Profile";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
        }
        .profile-container {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .profile-section {
            margin-bottom: 30px;
        }
        .nav-tabs {
            margin-bottom: 20px;
        }
        .tab-content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
        }
        .order-card {
            margin-bottom: 15px;
            border-left: 4px solid #1d3557;
        }
        .order-status {
            font-weight: bold;
        }
        .status-delivered {
            color: #28a745;
        }
        .status-pending {
            color: #ffc107;
        }
        .status-canceled {
            color: #dc3545;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Include navbar -->
    <?php include "Includes/templates/navbar.php"; ?>

    <div class="main-content">
        <div class="container mt-4">
            <h1 class="mb-4">My Profile</h1>
            
            <?php echo $update_message; ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="profile-container">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                            <h3 class="mt-3"><?php echo $client['client_name']; ?></h3>
                            <p class="text-muted"><?php echo $client['client_email']; ?></p>
                        </div>
                        <div class="list-group">
                            <a href="#profile-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">Profile Information</a>
                            <a href="#edit-profile" class="list-group-item list-group-item-action" data-bs-toggle="list">Edit Profile</a>
                            <a href="#change-password" class="list-group-item list-group-item-action" data-bs-toggle="list">Change Password</a>
                              </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="tab-content">
                        <!-- Profile Information Tab -->
                        <div class="tab-pane fade show active" id="profile-info">
                            <div class="profile-container">
                                <h3>Profile Information</h3>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Name:</strong></div>
                                    <div class="col-md-8"><?php echo $client['client_name']; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Email:</strong></div>
                                    <div class="col-md-8"><?php echo $client['client_email']; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Phone:</strong></div>
                                    <div class="col-md-8"><?php echo $client['client_phone']; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Address:</strong></div>
                                    <div class="col-md-8"><?php echo $client['client_address']; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>City:</strong></div>
                                    <div class="col-md-8"><?php echo $client['client_city']; ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Zip Code:</strong></div>
                                    <div class="col-md-8"><?php echo $client['client_zipcode']; ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Edit Profile Tab -->
                        <div class="tab-pane fade" id="edit-profile">
                            <div class="profile-container">
                                <h3>Edit Profile</h3>
                                <hr>
                                <form method="POST" action="profile.php">
                                    <div class="mb-3">
                                        <label for="client_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="client_name" name="client_name" value="<?php echo $client['client_name']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="client_email" name="client_email" value="<?php echo $client['client_email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="client_phone" name="client_phone" value="<?php echo $client['client_phone']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="client_address" name="client_address" value="<?php echo $client['client_address']; ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="client_city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="client_city" name="client_city" value="<?php echo $client['client_city']; ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="client_zipcode" class="form-label">Zip Code</label>
                                            <input type="number" class="form-control" id="client_zipcode" name="client_zipcode" value="<?php echo $client['client_zipcode']; ?>" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="change-password">
                            <div class="profile-container">
                                <h3>Change Password</h3>
                                <hr>
                                <?php echo $password_message; ?>
                                <form method="POST" action="profile.php">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                                </form>
                            </div>
                        </div>
                        
                      
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('.list-group-item'))
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)
                
                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
                    
                    // Update active state
                    triggerTabList.forEach(function(el) {
                        el.classList.remove('active')
                    })
                    triggerEl.classList.add('active')
                })
            })
        })
    </script>
</body>
</html>