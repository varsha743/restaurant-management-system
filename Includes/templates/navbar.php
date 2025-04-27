<?php
// Start the session at the beginning of the script
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}?>

<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 280px;
        background: #1d3557;
        padding: 2rem 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .sidebar-logo {
        text-align: center;
        padding: 0 1rem 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fca311;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin: 0.5rem 0;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        padding: 1rem 2rem;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s;
        position: relative;
    }

    .sidebar-nav a:hover, .sidebar-nav a.active {
        color: #fca311;
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-nav a i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .main-content {
        margin-left: 280px;
        transition: all 0.3s ease;
    }

    .menu-toggle {
        display: none;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1001;
        background: #1d3557;
        color: #ffffff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .sidebar {
            width: 0;
            overflow: hidden;
        }
        
        .sidebar.active {
            width: 280px;
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .menu-toggle {
            display: block;
        }
    }
</style>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <div class="sidebar-logo">
        <img src="Design/images/restaurant-logo1.jpg" alt="Restaurant Logo">
    </div>
    <ul class="sidebar-nav">
        <li><a href="index.php#home"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="index.php#menus"><i class="fas fa-utensils"></i> Our Menu</a></li>
        <li><a href="index.php#gallery"><i class="fas fa-images"></i> Gallery</a></li>
        <li><a href="index.php#contact"><i class="fas fa-envelope"></i> Contact</a></li>
        <?php if (isset($_SESSION['client_id'])): ?>
            <li><a href="table-reservation.php"><i class="fas fa-chair"></i> Book a Table</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="my_orders.php"><i class="fas fa-shopping-cart"></i> My Orders</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Mobile Menu Toggle -->
<button class="menu-toggle">
    <i class="fas fa-bars"></i>
</button>

<script>
    // Toggle sidebar on mobile
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992) {
            if (!e.target.closest('.sidebar') && !e.target.closest('.menu-toggle')) {
                document.querySelector('.sidebar').classList.remove('active');
            }
        }
    });

    // Set active link based on current page and hash
    function updateActiveLink() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.php';
        const currentHash = window.location.hash;
        
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.classList.remove('active');
            
            const linkHref = link.getAttribute('href');
            
            // For pages without hash, check full page match
            if (!linkHref.includes('#') && linkHref.includes(currentPage)) {
                link.classList.add('active');
            }
            // For pages with hash, check both page and hash match
            else if (linkHref.includes('#')) {
                const [pageUrl, hash] = linkHref.split('#');
                if (pageUrl.includes(currentPage) && (currentHash === `#${hash}` || (!currentHash && linkHref.includes('index.php#home')))) {
                    link.classList.add('active');
                }
            }
        });
    }

    // Update active link on page load
    updateActiveLink();

    // Update active link when hash changes
    window.addEventListener('hashchange', updateActiveLink);

    // Update active link when clicking on sidebar links
    document.querySelectorAll('.sidebar-nav a[href*="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Let the browser handle the navigation, then update the active state
            setTimeout(updateActiveLink, 100);
        });
    });
</script>