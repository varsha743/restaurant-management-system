<!-- PHP INCLUDES -->
<?php
    session_start();

    if (!isset($_SESSION['client_id'])) {
        $_SESSION['error_message'] = "You need to log in before placing an order.";
        header("Location: login.php");
        exit();
    }

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";

    //Getting website settings
    $stmt_web_settings = $con->prepare("SELECT * FROM website_settings");
    $stmt_web_settings->execute();
    $web_settings = $stmt_web_settings->fetchAll();

    $restaurant_name = "";
    $restaurant_email = "";
    $restaurant_address = "";
    $restaurant_phonenumber = "";

    foreach ($web_settings as $option) {
        if($option['option_name'] == 'restaurant_name') {
            $restaurant_name = $option['option_value'];
        } elseif($option['option_name'] == 'restaurant_email') {
            $restaurant_email = $option['option_value'];
        } elseif($option['option_name'] == 'restaurant_phonenumber') {
            $restaurant_phonenumber = $option['option_value'];
        } elseif($option['option_name'] == 'restaurant_address') {
            $restaurant_address = $option['option_value'];
        }
    }

    // Check if the user is logged in
    if(isset($_SESSION['client_id'])) {
        $client_id = $_SESSION['client_id'];
        $stmt_client = $con->prepare("SELECT * FROM clients WHERE client_id = ?");
        $stmt_client->execute(array($client_id));
        $client = $stmt_client->fetch();
    }
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap');

    :root {
        --primary-color: #ff6b35;
        --secondary-color: #1d3557;
        --accent-color: #fca311;
        --text-color: #333;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --border-radius: 15px;
        --shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: var(--text-color);
        background: var(--light-gray);
    }

    /* Main content offset for sidebar */
    .order-page {
        margin-left: 280px;
        transition: margin-left 0.3s ease;
    }

    @media (max-width: 992px) {
        .order-page {
            margin-left: 0;
        }
    }

    /* Hero Section */
    .order-hero {
        background: linear-gradient(rgba(29, 53, 87, 0.9), rgba(29, 53, 87, 0.9)), 
                    url('Design/images/home-background.jpg') center/cover no-repeat;

        padding: 180px 0 80px;
        color: var(--white);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .order-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,100 C60,50 70,50 100,100" fill="%23f8f9fa"/></svg>');
        background-size: cover;
        opacity: 0.1;
    }

    .order-hero h1 {
        font-family: 'Dancing Script', cursive;
        font-size: 4rem;
        color: var(--accent-color);
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
        animation: fadeInDown 1s ease;
    }

    .order-hero p {
        font-size: 1.2rem;
        position: relative;
        z-index: 1;
        animation: fadeInUp 1s ease;
    }

    /* Search and Filter Section */
    .search-filter-section {
        background: var(--white);
        padding: 30px;
        margin-top: -50px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        position: relative;
        z-index: 2;
        margin-bottom: 40px;
    }

    .search-filter-row {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 12px 20px 12px 50px;
        border-radius: 50px;
        border: 2px solid var(--light-gray);
        font-size: 1rem;
        transition: var(--transition);
    }

    .search-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.2);
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .filter-dropdown {
        min-width: 200px;
    }

    .filter-select {
        width: 100%;
        padding: 12px 20px;
        border-radius: 50px;
        border: 2px solid var(--light-gray);
        font-size: 1rem;
        appearance: none;
        background: var(--white) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23666"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 20px center;
        background-size: 20px;
        transition: var(--transition);
    }

    .filter-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.2);
    }

    /* Category Pills */
    .category-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 20px 0;
        justify-content: center;
    }

    .category-pill {
        background: var(--white);
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        font-weight: 600;
        color: var(--text-color);
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .category-pill:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.2);
        color: var(--primary-color);
    }

    .category-pill.active {
        background: var(--primary-color);
        color: var(--white);
    }

    .category-pill i {
        font-size: 1.2rem;
    }

    /* Menu Grid */
    .menu-container {
        padding: 60px 0;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .menu-item {
        background: var(--white);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }

    .menu-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .menu-item-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .menu-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .menu-item:hover .menu-item-image img {
        transform: scale(1.1);
    }

    .menu-item-content {
        padding: 20px;
    }

    .menu-item-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--secondary-color);
    }

    .menu-item-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
        height: 45px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .menu-item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .menu-item-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-btn {
        background: var(--light-gray);
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
    }

    .quantity-btn:hover {
        background: var(--primary-color);
        color: var(--white);
    }

    .quantity-input {
        width: 40px;
        height: 35px;
        text-align: center;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        font-weight: 600;
    }

    .add-to-cart-btn {
        background: var(--primary-color);
        color: var(--white);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .add-to-cart-btn:hover {
        background: #e55a28;
        transform: translateY(-2px);
    }

    /* Floating Cart */
    .floating-cart {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .cart-toggle-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-color);
        color: var(--white);
        border: none;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cart-toggle-btn:hover {
        transform: scale(1.1);
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--secondary-color);
        color: var(--white);
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .cart-dropdown {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 400px;
        background: var(--white);
        border-radius: var(--border-radius);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transform: translateY(20px);
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
    }

    .cart-dropdown.active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    .cart-header {
        padding: 20px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--secondary-color);
    }

    .cart-body {
        max-height: 400px;
        overflow-y: auto;
    }

    .cart-item {
        padding: 15px 20px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        align-items: center;
        gap: 15px;
        animation: slideInRight 0.3s ease;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-name {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .cart-item-price {
        color: var(--primary-color);
        font-weight: 600;
    }

    .cart-item-quantity {
        color: #666;
        font-size: 0.9rem;
    }

    .cart-item-remove {
        color: #dc3545;
        cursor: pointer;
        transition: var(--transition);
    }

    .cart-item-remove:hover {
        color: #c82333;
    }

    .cart-footer {
        padding: 20px;
        border-top: 1px solid var(--light-gray);
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .cart-total-amount {
        color: var(--primary-color);
        font-size: 1.3rem;
    }

    .checkout-btn {
        width: 100%;
        padding: 12px;
        background: var(--primary-color);
        color: var(--white);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .checkout-btn:hover {
        background: #e55a28;
    }

    /* Checkout Modal */
    .checkout-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }

    .checkout-modal.active {
        display: flex;
    }

    .checkout-content {
        background: var(--white);
        border-radius: var(--border-radius);
        padding: 30px;
        width: 90%;
        max-width: 500px;
        animation: zoomIn 0.3s ease;
    }

    .checkout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .checkout-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--secondary-color);
    }

    .close-checkout {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.2);
    }

    .place-order-btn {
        width: 100%;
        padding: 15px;
        background: var(--primary-color);
        color: var(--white);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .place-order-btn:hover {
        background: #e55a28;
    }

    /* Success Modal */
    .success-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 3000;
        justify-content: center;
        align-items: center;
    }

    .success-modal.active {
        display: flex;
    }

    .success-content {
        background: var(--white);
        border-radius: var(--border-radius);
        padding: 40px;
        width: 90%;
        max-width: 450px;
        text-align: center;
        animation: zoomIn 0.3s ease;
    }

    .success-icon {
        font-size: 4rem;
        color: #28a745;
        margin-bottom: 20px;
    }

    .success-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .success-message {
        color: #666;
        margin-bottom: 30px;
    }

    .success-btn {
        padding: 12px 30px;
        background: var(--primary-color);
        color: var(--white);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
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

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .pulse {
        animation: pulse 0.5s ease;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .order-hero h1 {
            font-size: 3rem;
        }

        .menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }

        .cart-dropdown {
            width: 350px;
        }

        .floating-cart {
            bottom: 20px;
            right: 20px;
        }
    }

    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        }

        .cart-dropdown {
            width: 350px;
        }

        .floating-cart {
            bottom: 20px;
            right: 20px;
        }
    }

    @media (max-width: 480px) {
        .order-hero h1 {
            font-size: 2.5rem;
        }

        .menu-grid {
            grid-template-columns: 1fr;
        }

        .cart-dropdown {
            width: 100%;
            right: 0;
            left: 0;
            border-radius: 20px 20px 0 0;
            bottom: 0;
        }

        .floating-cart {
            bottom: 100px;
            right: 20px;
        }

        .category-pills {
            margin: 20px 0;
        }

        .category-pill {
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .search-filter-row {
            flex-direction: column;
            gap: 15px;
        }

        .search-box, .filter-dropdown {
            width: 100%;
        }
    }
</style>

<main class="order-page">
    <!-- Hero Section -->
    <section class="order-hero">
        <div class="container">
            <h1>Order Now</h1>
            <p>Bringing the authentic taste of mom's cooking to your doorstep</p>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <div class="container">
        <div class="search-filter-section">
            <div class="search-filter-row">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="menu-search" placeholder="Search for dishes...">
                </div>
                <div class="filter-dropdown">
                    <select class="filter-select" id="price-filter">
                        <option value="">Price: All</option>
                        <option value="low">Price: Low to High</option>
                        <option value="high">Price: High to Low</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select class="filter-select" id="category-filter">
                        <option value="">Category: All</option>
                        <?php
                            $stmt = $con->prepare("SELECT * FROM menu_categories");
                            $stmt->execute();
                            $categories = $stmt->fetchAll();
                            
                            foreach($categories as $category) {
                                echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Pills -->
   

    <!-- Menu Items -->
    <section class="menu-container">
        <div class="container">
            <div class="menu-grid" id="menu-items">
                <?php
                    // Get all menu items
                    $stmt_menus = $con->prepare("SELECT * FROM menus ORDER BY category_id, menu_name");
                    $stmt_menus->execute();
                    $menus = $stmt_menus->fetchAll();

                    foreach($menus as $menu) {
                        $image_src = "manager/Uploads/images/".$menu['menu_image'];
                        ?>
                        <div class="menu-item" data-category="<?php echo $menu['category_id']; ?>" data-name="<?php echo strtolower($menu['menu_name']); ?>" data-price="<?php echo $menu['menu_price']; ?>" style="animation-delay: <?php echo rand(1, 10) / 10; ?>s;">
                            <div class="menu-item-image">
                                <img src="<?php echo $image_src; ?>" alt="<?php echo $menu['menu_name']; ?>" loading="lazy">
                            </div>
                            <div class="menu-item-content">
                                <h3 class="menu-item-title"><?php echo $menu['menu_name']; ?></h3>
                                <p class="menu-item-description"><?php echo $menu['menu_description']; ?></p>
                                <div class="menu-item-footer">
                                    <span class="menu-item-price">$<?php echo $menu['menu_price']; ?></span>
                                    <div class="quantity-controls">
                                        <button class="quantity-btn minus-btn" data-id="<?php echo $menu['menu_id']; ?>">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="quantity-input" id="quantity-<?php echo $menu['menu_id']; ?>" value="1" min="1" max="10" readonly>
                                        <button class="quantity-btn plus-btn" data-id="<?php echo $menu['menu_id']; ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <button class="add-to-cart-btn" data-id="<?php echo $menu['menu_id']; ?>" data-name="<?php echo htmlspecialchars($menu['menu_name']); ?>" data-price="<?php echo $menu['menu_price']; ?>" data-image="<?php echo $image_src; ?>">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>

    <!-- Floating Cart -->
    <div class="floating-cart">
        <button class="cart-toggle-btn" id="cart-toggle">
            <i class="fas fa-shopping-cart fa-lg"></i>
            <span class="cart-count">0</span>
        </button>
        <div class="cart-dropdown" id="cart-dropdown">
            <div class="cart-header">
                <span class="cart-title">Your Cart</span>
                <button class="btn btn-link text-danger" id="clear-cart">Clear All</button>
            </div>
            <div class="cart-body" id="cart-items">
                <p class="text-center py-4">Your cart is empty</p>
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="cart-total-amount">$0.00</span>
                </div>
                <button class="checkout-btn" id="checkout-btn" disabled>
                    Proceed to Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="checkout-modal" id="checkout-modal">
        <div class="checkout-content">
            <div class="checkout-header">
                <h2 class="checkout-title">Checkout</h2>
                <button class="close-checkout" id="close-checkout">&times;</button>
            </div>
            <form id="checkout-form">
                <?php if(!isset($_SESSION['client_id'])): ?>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="<?php echo $client['client_name']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?php echo $client['client_email']; ?>" readonly>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Special Instructions (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <p id="checkout-total" style="font-weight:600; font-size:16px; color:#333;">
                         <!-- Total will appear here -->
                    </p>
                </div>
                <button type="submit" class="place-order-btn">
                    <i class="fas fa-check-circle"></i> Place Order
                </button>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal" id="success-modal">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="success-title">Order Placed Successfully!</h3>
            <p class="success-message">Your order has been received and is being prepared. We'll notify you when it's on its way.</p>
            <button class="success-btn" id="success-ok">OK</button>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load cart from localStorage or initialize empty array
    let cart = JSON.parse(localStorage.getItem('restaurantCart')) || [];
    
    // DOM Elements
    const cartToggle = document.getElementById('cart-toggle');
    const cartDropdown = document.getElementById('cart-dropdown');
    const cartCount = document.querySelector('.cart-count');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.querySelector('.cart-total-amount');
    const checkoutBtn = document.getElementById('checkout-btn');
    const clearCartBtn = document.getElementById('clear-cart');
    const checkoutModal = document.getElementById('checkout-modal');
    const closeCheckoutBtn = document.getElementById('close-checkout');
    const checkoutForm = document.getElementById('checkout-form');
    const successModal = document.getElementById('success-modal');
    const successOkBtn = document.getElementById('success-ok');
    const categoryPills = document.querySelectorAll('.category-pill');
    const menuItems = document.querySelectorAll('.menu-item');
    const menuSearch = document.getElementById('menu-search');
    const priceFilter = document.getElementById('price-filter');
    const categoryFilter = document.getElementById('category-filter');

    // Save cart to localStorage
    function saveCartToStorage() {
        localStorage.setItem('restaurantCart', JSON.stringify(cart));
    }

    // Update cart display
    function updateCart() {
        if(cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-center py-4">Your cart is empty</p>';
            checkoutBtn.disabled = true;
        } else {
            let html = '';
            let total = 0;
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                
                html += `
                    <div class="cart-item" data-id="${item.id}">
                        <div class="cart-item-image">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                            <div class="cart-item-quantity">Quantity: ${item.quantity}</div>
                        </div>
                        <div class="cart-item-total">$${itemTotal.toFixed(2)}</div>
                        <button class="cart-item-remove" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            
            cartItemsContainer.innerHTML = html;
            cartTotal.textContent = `$${total.toFixed(2)}`;
            checkoutBtn.disabled = false;

            // Add event listeners to remove buttons
            document.querySelectorAll('.cart-item-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    removeFromCart(id);
                });
            });
        }

        // Update cart count
        const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = itemCount;
        
        // Save to localStorage
        saveCartToStorage();
    }

    // Remove item from cart
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        updateCart();
    }

    // Clear cart
    function clearCart() {
        cart = [];
        updateCart();
        localStorage.removeItem('restaurantCart');
    }

    // Initialize cart display
    updateCart();

    // Search functionality
    menuSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        menuItems.forEach(item => {
            const itemName = item.dataset.name.toLowerCase();
            const itemCategory = item.dataset.category;
            const currentCategory = document.querySelector('.category-pill.active')?.dataset.category;
            const priceFilterValue = priceFilter.value;
            const categoryFilterValue = categoryFilter.value;
            
            // Check if item matches search term and current filters
            const matchesSearch = itemName.includes(searchTerm);
            const matchesCategory = !categoryFilterValue || itemCategory === categoryFilterValue;
            const matchesActiveCategory = !currentCategory || itemCategory === currentCategory;
            
            if(matchesSearch && matchesCategory && matchesActiveCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Price filter functionality
    priceFilter.addEventListener('change', function() {
        const value = this.value;
        const menuItemsArray = Array.from(menuItems);
        
        if(value === 'low') {
            menuItemsArray.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
        } else if(value === 'high') {
            menuItemsArray.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
        }
        
        // Re-append items in sorted order
        const menuGrid = document.getElementById('menu-items');
        menuItemsArray.forEach(item => {
            menuGrid.appendChild(item);
        });
    });

    // Category filter functionality
    categoryFilter.addEventListener('change', function() {
        const categoryId = this.value;
        
        if(categoryId) {
            // Update active pill
            categoryPills.forEach(pill => {
                pill.classList.remove('active');
                if(pill.dataset.category === categoryId) {
                    pill.classList.add('active');
                }
            });
            
            filterByCategory(categoryId);
        } else {
            // Show all items
            menuItems.forEach(item => {
                item.style.display = 'block';
            });
        }
    });

    // Filter by category
    function filterByCategory(categoryId) {
        menuItems.forEach(item => {
            if(item.dataset.category === categoryId) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Category filtering
    categoryPills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Remove active class from all pills
            categoryPills.forEach(p => p.classList.remove('active'));
            // Add active class to clicked pill
            this.classList.add('active');
            
            const categoryId = this.dataset.category;
            // Update category filter dropdown
            categoryFilter.value = categoryId;
            
            // Filter menu items
            filterByCategory(categoryId);
        });
    });

    // Toggle cart dropdown
    cartToggle.addEventListener('click', function() {
        cartDropdown.classList.toggle('active');
    });

    // Close cart when clicking outside
    document.addEventListener('click', function(e) {
        if(!cartDropdown.contains(e.target) && !cartToggle.contains(e.target)) {
            cartDropdown.classList.remove('active');
        }
    });

    // Quantity controls
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.getElementById(`quantity-${id}`);
            if(input.value > 1) {
                input.value--;
            }
        });
    });

    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.getElementById(`quantity-${id}`);
            if(input.value < 10) {
                input.value++;
            }
        });
    });

    // Add to cart
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);
            const image = this.dataset.image;
            const quantity = parseInt(document.getElementById(`quantity-${id}`).value);

            // Check if item already in cart
            const existingItem = cart.find(item => item.id === id);
            
            if(existingItem) {
                existingItem.quantity += quantity;
            } else {
                console.log(id,name,price,image,quantity)
                cart.push({ id, name, price, image, quantity });
            }

            updateCart();
            
            // Add pulse animation to cart button
            cartToggle.classList.add('pulse');
            setTimeout(() => cartToggle.classList.remove('pulse'), 500);
            
            // Show cart dropdown
            cartDropdown.classList.add('active');
            
            // Reset quantity
            document.getElementById(`quantity-${id}`).value = 1;
            
            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-4';
            successMessage.style.zIndex = '9999';
            successMessage.textContent = 'Item added to cart!';
            document.body.appendChild(successMessage);
            
            setTimeout(() => {
                successMessage.remove();
            }, 2000);
        });
    });

    // Clear cart
    clearCartBtn.addEventListener('click', function() {
        if(cart.length > 0) {
            if(confirm('Are you sure you want to clear your cart?')) {
                clearCart();
            }
        }
    });

    // Open checkout modal
    checkoutBtn.addEventListener('click', function() {
        if(cart.length > 0) {
            checkoutModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('checkout-total').innerText = `Your total is $${totalAmount.toFixed(2)} and you need to pay at delivery`;
            
            // If user is logged in, pre-fill address
            <?php if(isset($_SESSION['client_id'])): ?>
                document.getElementById('address').value = '<?php echo $client['client_address']; ?>';
            <?php endif; ?>
        }
    });

    // Close checkout modal
    closeCheckoutBtn.addEventListener('click', function() {
        checkoutModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    // Close modal when clicking outside
    checkoutModal.addEventListener('click', function(e) {
        if(e.target === checkoutModal) {
            checkoutModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

    // Close success modal
    successOkBtn.addEventListener('click', function() {
        successModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    // Form submission
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        let isValid = true;
        
        <?php if(!isset($_SESSION['client_id'])): ?>
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            
            if(name === '') {
                isValid = false;
                alert('Please enter your name');
            }
            
            if(email === '' || !ValidateEmail(email)) {
                isValid = false;
                alert('Please enter a valid email address');
            }
            
            if(phone === '') {
                isValid = false;
                alert('Please enter your phone number');
            }
        <?php endif; ?>
        
        const address = document.getElementById('address').value.trim();
        if(address === '') {
            isValid = false;
            alert('Please enter your delivery address');
        }
        
        if(isValid) {
            // Prepare order data
            
            const orderData = {
                items: cart,
                total: cart.reduce((total, item) => total + (item.price * item.quantity), 0),
                customer: {
                    <?php if(isset($_SESSION['client_id'])): ?>
                        id: <?php echo $_SESSION['client_id']; ?>,
                        name: '<?php echo $client['client_name']; ?>',
                        email: '<?php echo $client['client_email']; ?>'
                    <?php else: ?>
                        name: document.getElementById('name').value.trim(),
                        email: document.getElementById('email').value.trim(),
                        phone: document.getElementById('phone').value.trim()
                    <?php endif; ?>
                },
                address: address,
                notes: document.getElementById('notes').value.trim()
            };
            console.log(orderData);
            // Send order to server
            fetch('Includes/php-files-ajax/place_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Close checkout modal
                    checkoutModal.classList.remove('active');
                    
                    // Show success modal
                    successModal.classList.add('active');
                    
                    // Clear cart
                    clearCart();
                } else {
                    alert('There was an error processing your order. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error processing your order. Please try again.');
            });
        }
    });

    // Email validation function
    function ValidateEmail(email) {
        const expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    }
});

    
</script>

<?php include "Includes/templates/footer.php"; ?>