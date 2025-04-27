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
    }

    /* Full Gallery Styles */
    .full-gallery-header {
        background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.9)), 
                    url('Design/images/home-background.jpg') center/cover no-repeat;
        padding: 8rem 0 4rem;
        color: var(--white);
        text-align: center;
        margin-top: -30px;
    }

    .full-gallery-header h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .full-gallery-section {
        padding: 4rem 0;
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

    .btn-back {
        background: var(--secondary-color);
        color: var(--white);
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back:hover {
        background: var(--primary-color);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
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
    <!-- Gallery Header -->
    <div class="full-gallery-header">
        <div class="container">
            <h1>Our Gallery</h1>
            <p>Explore our culinary masterpieces and restaurant ambiance</p>
        </div>
    </div>

    <!-- Full Gallery Section -->
    <section class="full-gallery-section">
        <div class="container">
            <div class="row g-4">
                <?php
                    $stmt_image_gallery = $con->prepare("Select * from image_gallery");
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
                <a href="index.php#gallery" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Lightbox Modal -->
<div class="gallery-modal" id="imageModal">
    <span class="gallery-modal-close">&times;</span>
    <img class="gallery-modal-content" id="modalImage">
</div>

<script>
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
</script>

<?php
    include "Includes/templates/footer.php";
?>