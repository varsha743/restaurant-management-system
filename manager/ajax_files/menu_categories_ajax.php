<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>

<?php
    if(isset($_POST['do']) && $_POST['do'] == "Add") {
        $category_name = test_input($_POST['category_name']);

        $checkItem = checkItem("category_name","menu_categories",$category_name);

        if($checkItem != 0) {
            $data['alert'] = "Warning";
            $data['message'] = "This category name already exists!";
            echo json_encode($data);
            exit();
        }
        elseif($checkItem == 0) {
            //Insert into the database
            $stmt = $con->prepare("INSERT INTO menu_categories(category_name) VALUES(?)");
            $stmt->execute(array($category_name));

            $data['alert'] = "Success";
            $data['message'] = "The new category has been inserted successfully!";
            echo json_encode($data);
            exit();
        }
    }

    if(isset($_POST['do']) && $_POST['do'] == "Delete") {
        $category_id = $_POST['category_id'];
        
        try {
            // Check if category is being used in menus
            $stmt = $con->prepare("SELECT COUNT(*) FROM menus WHERE category_id = ?");
            $stmt->execute(array($category_id));
            $count = $stmt->fetchColumn();
            
            if($count > 0) {
                echo "in_use";
                exit();
            }
            
            $stmt = $con->prepare("DELETE FROM menu_categories WHERE category_id = ?");
            $stmt->execute(array($category_id));
            echo "success";
        } catch(Exception $e) {
            echo "error: " . $e->getMessage();
        }
    }
?>