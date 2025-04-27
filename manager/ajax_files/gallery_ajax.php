<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>

<?php
    if(isset($_POST['do']) && $_POST['do'] == "Add") {
        $image_name = test_input($_POST['image_name']);

        if(empty($image_name)) {
            echo "<div class='alert alert-warning'>Image name is required!</div>";
            exit();
        }

        if(empty($_FILES['image']['name'])) {
            echo "<div class='alert alert-warning'>Please select an image!</div>";
            exit();
        }

        $image_Name = $_FILES['image']['name'];
        $image_allowed_extension = array("jpeg","jpg","png");
        $image_split = explode('.',$image_Name);
        $extension = end($image_split);
        $image_extension = strtolower($extension);
        
        if(!in_array($image_extension, $image_allowed_extension)) {
            echo "<div class='alert alert-warning'>Invalid Image format! Only JPEG, JPG and PNG are accepted.</div>";
            exit();
        }
        
        try {
            $image = rand(0,100000).'_'.$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],"../Uploads/images/".$image);
            
            //Insert into the database
            $stmt = $con->prepare("INSERT INTO image_gallery(image_name, image) VALUES(?, ?)");
            $stmt->execute(array($image_name, $image));

            echo "<div class='alert alert-success'>Great! Image has been inserted successfully.</div>";
        }
        catch(Exception $e) {
            echo "<div class='alert alert-danger'>Error occurred while trying to insert image!</div>";
        }
    }

    if(isset($_POST['do']) && $_POST['do'] == "Delete") {
        $image_id = $_POST['image_id'];
        
        try {
            // Get image name to delete file
            $stmt = $con->prepare("SELECT image FROM image_gallery WHERE image_id = ?");
            $stmt->execute(array($image_id));
            $image = $stmt->fetch();
            
            if(!$image) {
                echo "error_not_found";
                exit();
            }
            
            // Delete from database
            $stmt = $con->prepare("DELETE FROM image_gallery WHERE image_id = ?");
            $stmt->execute(array($image_id));
            
            // Delete image file
            if(file_exists("../Uploads/images/".$image['image'])) {
                unlink("../Uploads/images/".$image['image']);
            }
            
            echo "success";
        } catch(Exception $e) {
            echo "error: " . $e->getMessage();
        }
    }
?>