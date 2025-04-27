<!-- PHP INCLUDES -->
<?php
    // Start session
    session_start();

    // Include the database connection file
    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php"; // Added the navbar include

    // Check if user is logged in
    if (!isset($_SESSION['client_id'])) {
        // If not logged in, redirect to login page
        header("Location: login.php");
        exit;
    }

    // Retrieve user information from the database based on client_id
    $stmt = $con->prepare("SELECT * FROM clients WHERE client_id = ?");
    $stmt->execute([$_SESSION['client_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if (!$user) {
        // If user not found, redirect to login page
        header("Location: login.php");
        exit;
    }
?>
    
<style type="text/css">
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

    .table_reservation_section {
        max-width: 850px;
        margin: 50px auto;
        min-height: 500px;
        padding: 0 20px;
    }

    .reservation-hero {
        background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.9)), 
                    url('Design/images/reservation-bg.jpg') center/cover no-repeat;
        min-height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        position: relative;
        overflow: hidden;
        margin-top: 0;
        text-align: center;
    }

    .reservation-title {
        font-family: 'Dancing Script', cursive;
        font-size: 4rem;
        font-weight: 700;
        color: var(--accent-color);
        margin-bottom: 1rem;
    }

    .reservation-subtitle {
        font-size: 1.5rem;
        margin-bottom: 0;
        color: white;
    }

    .reservation-form {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        margin-top: -50px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .text_header {
        margin-bottom: 20px;
        font-size: 22px;
        font-weight: 600;
        color: var(--secondary-color);
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        background-color: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: none;
        border: 2px solid #eee;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(252, 163, 17, 0.2);
    }

    label {
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--secondary-color);
        display: block;
    }

    .btn-reservation {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        cursor: pointer;
        display: inline-block;
        margin-top: 10px;
    }

    .btn-reservation:hover {
        background: #e55a28;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    .reservation-info {
        background: var(--light-gray);
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
    }

    .reservation-info h3 {
        color: var(--secondary-color);
        font-size: 1.2rem;
        margin-bottom: 15px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }

    .reservation-info p {
        margin-bottom: 10px;
    }

    .reservation-info i {
        color: var(--primary-color);
        margin-right: 8px;
    }

    .alert {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .main-content {
        margin-left: 280px;
        transition: all 0.3s ease;
    }

    @media (max-width: 992px) {
        .main-content {
            margin-left: 0;
        }

        .reservation-title {
            font-size: 3rem;
        }
    }

    @media (max-width: 768px) {
        .reservation-form {
            padding: 1.5rem;
        }

        .reservation-title {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

<!-- Main Content -->
<main class="main-content">
    <!-- Hero Section -->
    <section class="reservation-hero">
        <div class="container">
            <h1 class="reservation-title">Book a Table</h1>
            <p class="reservation-subtitle">Reserve your perfect dining experience with us</p>
        </div>
    </section>

    <section class="table_reservation_section">
        <div class="container">
            <div class="reservation-form">
                <?php
                if(isset($_POST['submit_table_reservation_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    // Selected Date and Time
                    $selected_date = $_POST['selected_date'];
                    $selected_time = $_POST['selected_time'];
                    $desired_date = $selected_date." ".$selected_time;

                    // Number of Guests
                    $number_of_guests = $_POST['number_of_guests'];

                    // Table ID
                    $table_id = $_POST['table_id'];

                    // Use client details from the session
                    $client_id = $_SESSION['client_id'];
                    $client_full_name = $user['client_name'];
                    $client_phone_number = $user['client_phone'];
                    $client_email = $user['client_email'];

                    // Database transaction
                    $con->beginTransaction();
                    try {
                        $stmt_reservation = $con->prepare("INSERT INTO reservations(date_created, client_id, selected_time, nbr_guests, table_id) VALUES (?, ?, ?, ?, ?)");
                        $stmt_reservation->execute([Date("Y-m-d H:i"), $client_id, $desired_date, $number_of_guests, $table_id]);

                        echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Great! Your reservation has been created successfully.</div>";

                        $con->commit();
                    } catch (Exception $e) {
                        $con->rollBack();
                        echo "<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> " . $e->getMessage() . "</div>";
                    }
                }
                ?>

                <div class="text_header">
                    <span><i class="far fa-calendar-alt"></i> Select Your Reservation Details</span>
                </div>
                <form method="POST" action="table-reservation.php">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="reservation_date"><i class="far fa-calendar"></i> Date</label>
                                <input type="date" min="<?php echo date('Y-m-d',strtotime('+1 day')) ?>" name="selected_date" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="reservation_time"><i class="far fa-clock"></i> Time</label>
                                <input type="time" name="selected_time" class="form-control" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="number_of_guests"><i class="fas fa-users"></i> Number of Guests</label>
                                <input type="number" name="number_of_guests" class="form-control" required="required" min="1" max="20">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="table_id"><i class="fas fa-chair"></i> Table Number</label>
                                <select name="table_id" class="form-control" required="required">
                                    <?php for($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>">Table <?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" name="submit_table_reservation_form" class="btn-reservation">
                            <i class="fas fa-calendar-check"></i> Make a Reservation
                        </button>
                    </div>
                </form>

                <div class="reservation-info">
                    <h3><i class="fas fa-info-circle"></i> Reservation Information</h3>
                    <p><i class="fas fa-user"></i> <strong>Name:</strong> <?php echo htmlspecialchars($user['client_name']); ?></p>
                    <p><i class="fas fa-phone"></i> <strong>Phone:</strong> <?php echo htmlspecialchars($user['client_phone']); ?></p>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($user['client_email']); ?></p>
                    <p><i class="fas fa-clock"></i> <strong>Opening Hours:</strong> Monday - Friday: 11:30am - 9:00pm, Saturday: 11:30am - 10:00pm, Sunday: 12:00pm - 9:00pm</p>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- PHP INCLUDES -->
<?php include "Includes/templates/footer.php"; ?>