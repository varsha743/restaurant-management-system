<?php
    require_once '../../connect.php';
    require_once '../functions/functions.php';

    if(isset($_POST['contact_name']) && isset($_POST['contact_email']) && isset($_POST['contact_subject']) && isset($_POST['contact_message']))
    {
        $contact_name = test_input($_POST['contact_name']);
        $contact_email  = test_input($_POST['contact_email']);
        $contact_subject = test_input($_POST['contact_subject']);
        $contact_message = test_input($_POST['contact_message']);

        try {
            // Insert data into feedback table
            $stmt = $con->prepare("INSERT INTO feedback (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$contact_name, $contact_email, $contact_subject, $contact_message]);

            // Success message after database insert
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    The message has been sent successfully.
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
            exit;
        }
    } else {
        // Handle case where POST data is missing
        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                Please fill in all required fields before submitting.
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
    }
?>
