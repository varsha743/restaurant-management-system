<!-- PHP INCLUDES -->
<?php
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
?>

<style>
    :root {
        --primary-color: #ff6b35;
        --secondary-color: #1d3557;
        --accent-color: #fca311;
        --text-color: #333;
        --white: #ffffff;
        --light-gray: #f8f9fa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: var(--text-color);
        overflow-x: hidden;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.9)), 
                    url('Design/images/home-background.jpg') center/cover no-repeat;
        min-height: 100vh;
        display: flex;
        align-items: center;
        color: var(--white);
        position: relative;
        overflow: hidden;
        margin-top: -120px;
        padding-top: 120px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 2rem;
    }

    .hero-content h1 {
        font-family: 'Dancing Script', cursive;
        font-size: 4.5rem;
        font-weight: 700;
        color: var(--accent-color);
        margin-bottom: 1rem;
    }

    .hero-content h2 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .hero-content p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        max-width: 600px;
    }

    .btn-custom {
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        border: none;
        margin-right: 15px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary-custom {
        background: var(--primary-color);
        color: var(--white);
    }

    .btn-primary-custom:hover {
        background: #e55a28;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        color: var(--white);
    }

    .btn-outline-custom {
        background: transparent;
        border: 2px solid var(--white);
        color: var(--white);
    }

    .btn-outline-custom:hover {
        background: var(--white);
        color: var(--secondary-color);
    }

    /* Qualities Section */
    .qualities-section {
        padding: 6rem 0;
        background: var(--light-gray);
    }

    .quality-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        height: 100%;
    }

    .quality-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    .quality-card img {
        width: 100px;
        height: 100px;
        margin-bottom: 1.5rem;
    }

    .quality-card h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--secondary-color);
    }

    /* Menu Section */
    .menu-section {
        padding: 6rem 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 1rem;
    }

    .menu-tabs {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 3rem;
        gap: 15px;
    }

    .menu-tab-btn {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .menu-tab-btn.active,
    .menu-tab-btn:hover {
        background: var(--primary-color);
        color: var(--white);
    }

    .menu-card {
        background: var(--white);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        margin-bottom: 30px;
        height: 100%;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .menu-image {
        position: relative;
        overflow: hidden;
        height: 200px;
    }

    .menu-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .menu-card:hover .menu-image img {
        transform: scale(1.1);
    }

    .menu-info {
        padding: 1.5rem;
    }

    .menu-info h5 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--secondary-color);
    }

    .menu-info p {
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .menu-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* See All Button */
    .see-all-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
    }

    .btn-see-all {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        height: fit-content;
    }

    .btn-see-all:hover {
        background: var(--primary-color);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    /* Gallery Section */
    .gallery-section {
        padding: 6rem 0;
        background: var(--light-gray);
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .gallery-image {
        height: 300px;
        transition: transform 0.5s;
    }

    .gallery-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-item:hover .gallery-image {
        transform: scale(1.1);
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 107, 53, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    /* Contact Section */
    .contact-section {
        padding: 6rem 0;
    }

    .contact-info {
        background: var(--secondary-color);
        color: var(--white);
        padding: 3rem;
        border-radius: 15px;
        height: 100%;
    }

    .contact-info h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }

    .contact-info h3 {
        font-size: 1.2rem;
        margin: 2rem 0 1rem;
        color: var(--accent-color);
    }

    .contact-form {
        background: var(--light-gray);
        padding: 3rem;
        border-radius: 15px;
        height: 100%;
    }

    .form-control {
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 1rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.2);
        border-color: var(--primary-color);
    }

    /* Footer */
    .footer-section {
        background: var(--secondary-color);
        color: var(--white);
        padding: 4rem 0 2rem;
    }

    .footer-widget h3 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--accent-color);
    }

    .widget-social {
        display: flex;
        gap: 15px;
        list-style: none;
        padding: 0;
    }

    .widget-social a {
        color: var(--white);
        font-size: 1.5rem;
        transition: color 0.3s;
    }

    .widget-social a:hover {
        color: var(--accent-color);
    }

    /* Lightbox Modal */
    .gallery-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: auto;
    }

    .gallery-modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 800px;
        max-height: 90vh;
        object-fit: contain;
        animation: zoom 0.6s;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }

    .gallery-modal-close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .gallery-modal-close:hover,
    .gallery-modal-close:focus {
        color: var(--primary-color);
        text-decoration: none;
        cursor: pointer;
    }

    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }
</style>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1>Amma Chethi Vanta</h1>
                        <h2>Where Every Bite Tells a Story</h2>
                        <p>Experience the authentic taste of home-cooked meals with our carefully crafted recipes passed down through generations. Let our culinary journey take you back to your mother's kitchen.</p>
                        <div class="hero-buttons">
                            <a href="order_food.php" class="btn btn-custom btn-primary-custom">
                                Order Now <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <a href="#menus" class="btn btn-custom btn-outline-custom">
                                View Menu <i class="fas fa-utensils ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Qualities Section -->
    <section class="qualities-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="quality-card">
                        <img src="Design/images/quality_food_img.png" alt="Quality Food">
                        <h3>Premium Quality</h3>
                        <p>Only the finest ingredients make it to your plate. We believe in transparency and quality above all.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="quality-card">
                        <img src="Design/images/fast_delivery_img.png" alt="Fast Delivery">
                        <h3>Lightning Fast</h3>
                        <p>From our kitchen to your doorstep in record time. Hot, fresh, and always on time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="quality-card">
                        <img src="Design/images/original_taste_img.png" alt="Yummy">
                        <h3>Unforgettable Taste</h3>
                        <p>Every dish is a masterpiece of flavors, carefully balanced to create an unforgettable experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="menu-section" id="menus">
        <div class="container">
            <div class="section-title">
                <h2>Our Delicious Menu</h2>
                <p>Explore our culinary offerings crafted with love and expertise</p>
            </div>
            
            <div class="menu-tabs">
                <?php
                    $stmt = $con->prepare("Select * from menu_categories");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    $x = 0;
                    foreach($rows as $row) {
                        $active_class = ($x == 0) ? 'active' : '';
						echo "<button class='menu-tab-btn $active_class' onclick='showCategoryMenus(event,\"".str_replace(' ', '', $row['category_name'])."\")'>";                            echo $row['category_name'];
                        echo "</button>";
                        $x++;
                    }
                ?>
            </div>

            <div class="menu-content">
                <?php
                    $i = 0;
                    foreach($rows as $row) {
                        $display_style = ($i == 0) ? 'display:block' : 'display:none';
						echo '<div class="menu-category-content" id="'.str_replace(' ', '', $row['category_name']).'" style="'.$display_style.'">';                        
                        $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                        $stmt_menus->execute(array($row['category_id']));
                        $rows_menus = $stmt_menus->fetchAll();
                        $total_count = $stmt_menus->rowCount();

                        if($total_count == 0) {
                            echo "<div class='alert alert-info'>No menus available for this category.</div>";
                        } else {
                            echo "<div class='row g-4'>";
                            $item_count = 0;
                            foreach($rows_menus as $menu) {
                                // Only show 2 items
                                if($item_count < 2) {
                                    $source = "manager/Uploads/images/".$menu['menu_image'];
                                    ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="menu-card">
                                            <div class="menu-image">
                                                <img src="<?php echo $source; ?>" alt="<?php echo $menu['menu_name']; ?>">
                                            </div>
                                            <div class="menu-info">
                                                <h5><?php echo $menu['menu_name']; ?></h5>
                                                <p><?php echo $menu['menu_description']; ?></p>
                                                <div class="menu-price">$<?php echo $menu['menu_price']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                $item_count++;
                            }
                            // Add see all button as a card
                            echo '<div class="col-md-6 col-lg-4">';
                                echo '<div class="see-all-container" style="height: 100%; align-items: center;">';
                                    echo '<a href="order_food.php" class="btn-see-all">';
                                        echo 'See All <i class="fas fa-arrow-right ms-2"></i>';
                                    echo '</a>';
                                echo '</div>';
                            echo '</div>';
                            echo "</div>";
                        }
                        echo '</div>';
                        $i++;
                    }
                ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <div class="section-title">
                <h2>Visual Journey</h2>
                <p>A glimpse into our culinary world</p>
            </div>
            <div class="row g-4">
                <?php
                    $stmt_image_gallery = $con->prepare("Select * from image_gallery LIMIT 5");
                    $stmt_image_gallery->execute();
                    $rows_image_gallery = $stmt_image_gallery->fetchAll();

                    foreach($rows_image_gallery as $row_image_gallery) {
                        $source = "manager/Uploads/images/".$row_image_gallery['image'];
                        echo "<div class='col-md-6 col-lg-4'>";
                            echo "<div class='gallery-item' onclick='openModal(\"" . htmlspecialchars($source, ENT_QUOTES) . "\")'>";
                                echo "<div class='gallery-image'>";
                                    echo "<img src='".$source."' alt='Gallery Image'>";
                                echo "</div>";
                                echo "<div class='gallery-overlay'>";
                                    echo "<i class='fas fa-expand fa-2x text-white'></i>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
            <div class="text-center mt-5">
                <a href="full_gallery.php" class="btn btn-custom btn-primary-custom">
                    See All <i class="fas fa-images ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="section-title">
                <h2>Get in Touch</h2>
                <p>We'd love to hear from you</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h2>Let's Connect</h2>
                        <p>Whether you have a question about our menu, need to make a special request, or just want to share feedback, we're here to help!</p>
                        <h3>Address</h3>
                        <p><?php echo $restaurant_address; ?></p>
                        <h3>Contact Details</h3>
                        <p>Email: <?php echo $restaurant_email; ?><br>
                        Phone: <?php echo $restaurant_phonenumber; ?></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form">
                        <form id="contact_form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="contact_name" placeholder="Your Name" oninput="document.getElementById('invalid-name').innerHTML = ''" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');">
                                    <div class="invalid-feedback" id="invalid-name" style="display: block"></div>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" id="contact_email" placeholder="Your Email" oninput="document.getElementById('invalid-email').innerHTML = ''">
                                    <div class="invalid-feedback" id="invalid-email" style="display: block"></div>
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="contact_subject" placeholder="Subject" oninput="document.getElementById('invalid-subject').innerHTML = ''" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');">
                                    <div class="invalid-feedback" id="invalid-subject" style="display: block"></div>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" id="contact_message" rows="5" placeholder="Your Message" oninput="document.getElementById('invalid-message').innerHTML = ''"></textarea>
                                    <div class="invalid-feedback" id="invalid-message" style="display: block"></div>
                                </div>
                                <div class="col-12">
                                    <button type="button" id="contact_send" class="btn btn-custom btn-primary-custom w-100">
                                        Send Message <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="sending_load" class="text-center mt-3" style="display: none;">Sending...</div>
                            <div id="contact_status_message" class="mt-3"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-widget">
                        <img src="Design/images/restaurant-logo1.jpg" alt="Restaurant Logo" class="mb-4" style="width: 150px; border-radius: 50%;">
                        <p>Experience the authentic taste of home-cooked meals with our carefully crafted recipes.</p>
                        <ul class="widget-social mt-4">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-widget">
                        <h3>Contact Info</h3>
                        <p><?php echo $restaurant_address; ?></p>
                        <p><?php echo $restaurant_email; ?><br><?php echo $restaurant_phonenumber; ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-widget">
                        <h3>Opening Hours</h3>
                        <ul class="list-unstyled">
                            <li>Monday - Friday: 11:30am - 9:00pm</li>
                            <li>Saturday: 11:30am - 10:00pm</li>
                            <li>Sunday: 12:00pm - 9:00pm</li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Amma Chethi Vanta. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</main>

<!-- Lightbox Modal -->
<div class="gallery-modal" id="imageModal">
    <span class="gallery-modal-close">&times;</span>
    <img class="gallery-modal-content" id="modalImage">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Menu Tab Functionality
    function showCategoryMenus(event, categoryName) {
        event.preventDefault();
        
        // Hide all menu content
        document.querySelectorAll('.menu-category-content').forEach(function(content) {
            content.style.display = 'none';
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.menu-tab-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        
        // Show selected menu content
        var selectedCategory = document.getElementById(categoryName);
        if (selectedCategory) {
            selectedCategory.style.display = 'block';
            event.currentTarget.classList.add('active');
        } else {
            console.error('Category not found:', categoryName);
        }
    }

    // Gallery Modal Functionality
    function openModal(imageSrc) {
        document.getElementById('imageModal').style.display = "block";
        document.getElementById('modalImage').src = imageSrc;
    }

    // Close modal when clicking on the X button
    document.querySelector('.gallery-modal-close').onclick = function() {
        document.getElementById('imageModal').style.display = "none";
    }

    // Close modal when clicking outside the image
    window.onclick = function(event) {
        if (event.target == document.getElementById('imageModal')) {
            document.getElementById('imageModal').style.display = "none";
        }
    }

    // Close modal with escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            document.getElementById('imageModal').style.display = "none";
        }
    });

    // Contact Form Functionality
    $(document).ready(function() {
        $('#contact_send').click(function() {
            var contact_name = $('#contact_name').val();
            var contact_email = $('#contact_email').val();
            var contact_subject = $('#contact_subject').val();
            var contact_message = $('#contact_message').val();

            var flag = 0;

            // Validation
            if($.trim(contact_name) == "") {
                $('#invalid-name').text('This is a required field!').show();
                flag = 1;
            } else if(contact_name.length < 5) {
                $('#invalid-name').text('Length is less than 5 letters!').show();
                flag = 1;
            } else {
                $('#invalid-name').hide();
            }

            if(!ValidateEmail(contact_email)) {
                $('#invalid-email').text('Invalid e-mail!').show();
                flag = 1;
            } else {
                $('#invalid-email').hide();
            }

            if($.trim(contact_subject) == "") {
                $('#invalid-subject').text('This is a required field!').show();
                flag = 1;
            } else {
                $('#invalid-subject').hide();
            }

            if($.trim(contact_message) == "") {
                $('#invalid-message').text('This is a required field!').show();
                flag = 1;
            } else {
                $('#invalid-message').hide();
            }

            if(flag == 0) {
                $('#sending_load').show();

                $.ajax({
                    url: "Includes/php-files-ajax/contact.php",
                    type: "POST",
                    data: {
                        contact_name: contact_name,
                        contact_email: contact_email,
                        contact_subject: contact_subject,
                        contact_message: contact_message
                    },
                    success: function(data) {
                        $('#contact_status_message').html(data);
                    },
                    beforeSend: function() {
                        $('#sending_load').show();
                    },
                    complete: function() {
                        $('#sending_load').hide();
                    },
                    error: function(xhr, status, error) {
                        alert("Internal ERROR has occured, please, try later!");
                    }
                });
            }
        });
    });

    // Email validation function
    function ValidateEmail(email) {
		       var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    }

	
</script>

