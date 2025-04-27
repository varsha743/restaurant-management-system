<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in
if (!isset($_SESSION['client_id'])) {
    $_SESSION['error_message'] = "You need to log in to view your orders.";
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "restaurant_website");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders for the logged-in client
$client_id = $_SESSION['client_id'];
$sql = "SELECT po.*, 
        (SELECT COUNT(*) FROM in_order WHERE order_id = po.order_id) as item_count,
        (SELECT SUM(m.menu_price * io.quantity) FROM in_order io 
         JOIN menus m ON io.menu_id = m.menu_id 
         WHERE io.order_id = po.order_id) as total_price
        FROM placed_orders po 
        WHERE po.client_id = ? 
        ORDER BY po.order_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch client info
$sql_client = "SELECT * FROM clients WHERE client_id = ?";
$stmt_client = $conn->prepare($sql_client);
$stmt_client->bind_param("i", $client_id);
$stmt_client->execute();
$client_info = $stmt_client->get_result()->fetch_assoc();

// Function to get order items
function getOrderItems($conn, $order_id) {
    $sql = "SELECT io.*, m.menu_name, m.menu_price, m.menu_image 
            FROM in_order io 
            JOIN menus m ON io.menu_id = m.menu_id 
            WHERE io.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_order'])) {
    $order_id = intval($_POST['order_id']);
    $reason = trim($_POST['cancellation_reason']);

    $stmt = $conn->prepare("UPDATE placed_orders 
                            SET canceled = 1, cancellation_reason = ? 
                            WHERE order_id = ? AND client_id = ? AND canceled = 0 AND delivered = 0");
    $stmt->bind_param("sii", $reason, $order_id, $_SESSION['client_id']);
    $stmt->execute();

    header("Location: my_orders.php");  // Refresh to show updated status
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Vincent Pizza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF5722;
            --secondary-color: #2C3E50;
            --accent-color: #3498DB;
            --background-color: #F8F9FA;
            --text-color: #333333;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --border-radius: 16px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Modern Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), #ff8a65);
            color: white;
            padding: 40px;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeInDown 0.8s ease;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title i {
            font-size: 2rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 8px;
        }

        /* Modern Order Cards */
        .orders-container {
            display: grid;
            gap: 25px;
            margin-bottom: 50px;
        }

        .order-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            background: var(--secondary-color);
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .order-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            pointer-events: none;
        }

        .order-id {
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-id i {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .order-date {
            font-size: 0.9rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-status {
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-pending {
            background-color: var(--warning-color);
            color: white;
        }

        .status-delivered {
            background-color: var(--success-color);
            color: white;
        }

        .status-canceled {
            background-color: var(--danger-color);
            color: white;
        }

        .order-body {
            padding: 25px;
        }

        .order-info {
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .info-label {
            font-weight: 600;
            width: 160px;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-label i {
            font-size: 1rem;
            color: var(--primary-color);
        }

        .info-value {
            flex: 1;
            color: #555;
        }

        .order-items {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .order-items h3 {
            font-size: 1.2rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-items h3 i {
            color: var(--primary-color);
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .order-item:hover {
            background-color: rgba(255, 87, 34, 0.03);
            padding-left: 10px;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            margin-right: 20px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        .item-price {
            font-size: 0.95rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .item-quantity {
            background: var(--primary-color);
            color: white;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .order-summary {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #e2e8f0;
        }

        .order-total {
            background: linear-gradient(135deg, var(--primary-color), #ff8a65);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(255, 87, 34, 0.2);
        }

        .order-total i {
            font-size: 1rem;
        }

        .no-orders {
            text-align: center;
            margin: 100px 0;
            color: #666;
            animation: fadeIn 1s ease;
        }

        .no-orders i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
            animation: bounce 2s infinite;
        }

        .no-orders p {
            font-size: 1.2rem;
            margin-bottom: 25px;
        }

        .cancel-btn {
            background: linear-gradient(135deg, var(--danger-color), #ff6b6b);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cancel-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        /* Modern Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            width: 450px;
            max-width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header h3 {
            color: var(--secondary-color);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-header i {
            color: var(--danger-color);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            resize: vertical;
            min-height: 120px;
            font-size: 15px;
            transition: var(--transition);
        }

        .form-group textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: var(--secondary-color);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ff6b6b);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                padding: 30px;
            }

            .page-title {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }

            .order-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .order-date {
                order: -1;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .item-image {
                width: 50px;
                height: 50px;
            }

            .order-item {
                flex-wrap: wrap;
            }

            .item-quantity {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include "Includes/templates/navbar.php"; ?>

    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-history"></i>
                        My Orders
                    </h1>
                    <p class="page-subtitle">Track and manage your current and past orders</p>
                </div>
                <div class="header-actions">
                    <?php if ($result->num_rows > 0): ?>
                        <span style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">
                            <i class="fas fa-shopping-bag"></i> 
                            <?php echo $result->num_rows; ?> orders
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="orders-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">
                                    <i class="fas fa-receipt"></i>
                                    Order #<?php echo $order['order_id']; ?>
                                </div>
                                <div class="order-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo date('F j, Y, g:i a', strtotime($order['order_time'])); ?>
                                </div>
                                <?php if ($order['canceled']): ?>
                                    <div class="order-status status-canceled">
                                        <i class="fas fa-times-circle"></i> Canceled
                                    </div>
                                <?php elseif ($order['delivered']): ?>
                                    <div class="order-status status-delivered">
                                        <i class="fas fa-check-circle"></i> Delivered
                                    </div>
                                <?php else: ?>
                                    <div class="order-status status-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="order-body">
                                <div class="order-info">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Delivery Address:
                                        </div>
                                        <div class="info-value"><?php echo htmlspecialchars($order['delivery_address']); ?></div>
                                    </div>
                                    <?php if ($order['canceled']): ?>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Cancellation Reason:
                                            </div>
                                            <div class="info-value"><?php echo htmlspecialchars($order['cancellation_reason'] ?: 'No reason provided'); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="order-items">
                                    <h3><i class="fas fa-utensils"></i> Order Items</h3>
                                    <?php 
                                        $items = getOrderItems($conn, $order['order_id']);
                                        $total = 0;
                                        while ($item = $items->fetch_assoc()):
                                            $total += $item['menu_price'] * $item['quantity'];
                                    ?>
                                        <div class="order-item">
                                            <img class="item-image" src="admin/Uploads/images/<?php echo $item['menu_image']; ?>" alt="<?php echo $item['menu_name']; ?>">
                                            <div class="item-details">
                                                <div class="item-name"><?php echo $item['menu_name']; ?></div>
                                                <div class="item-price">$<?php echo number_format($item['menu_price'], 2); ?></div>
                                            </div>
                                            <div class="item-quantity">×<?php echo $item['quantity']; ?></div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                
                                <div class="order-summary">
                                    <div class="order-total">
                                        <i class="fas fa-wallet"></i>
                                        Total: $<?php echo number_format($total, 2); ?>
                                    </div>
                                </div>
                                
                                <?php if (!$order['canceled'] && !$order['delivered']): ?>
                                    <div style="text-align: right; margin-top: 20px;">
                                        <button class="cancel-btn" onclick="openCancelModal(<?php echo $order['order_id']; ?>)">
                                            <i class="fas fa-times"></i> Cancel Order
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-orders">
                        <i class="fas fa-shopping-basket"></i>
                        <p>You haven't placed any orders yet.</p>
                        <a href="index.php#menus" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-color), #ff8a65); color: white; text-decoration: none;">
                            <i class="fas fa-utensils"></i> Browse Menu
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Cancel Order Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Cancel Order</h3>
            </div>
            <form id="cancelForm" method="post" action="my_orders.php">
                <input type="hidden" id="order_id" name="order_id" value="">
                <input type="hidden" name="cancel_order" value="1">
                <div class="form-group">
                    <label for="cancellation_reason">
                        <i class="fas fa-comment-alt"></i>
                        Reason for cancellation (optional):
                    </label>
                    <textarea id="cancellation_reason" name="cancellation_reason" placeholder="Please let us know why you're canceling this order..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">
                        <i class="fas fa-arrow-left"></i> Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openCancelModal(orderId) {
            document.getElementById('order_id').value = orderId;
            document.getElementById('cancelModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal if user clicks outside
        window.onclick = function(event) {
            const modal = document.getElementById('cancelModal');
            if (event.target === modal) {
                closeCancelModal();
            }
        }
        
        // Add keyboard support for modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCancelModal();
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>