<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	
	if(isset($_POST['do']) && $_POST['do'] == "Add")
	{
        $supplier_name = test_input($_POST['supplier_name']);
        $supplier_number = test_input($_POST['supplier_number']);
        $checkItem = checkItem("supplier_name","supplier",$supplier_name);

        if($checkItem != 0)
        {
            $data['alert'] = "Warning";
            $data['message'] = "This supplier name already exists!";
            echo json_encode($data);
            exit();
        }
        elseif($checkItem == 0)
        {
        	//Insert into the database
            $stmt = $con->prepare("insert into supplier(supplier_name, supplier_number) values(?,?) ");
            $stmt->execute(array($supplier_name, $supplier_number));


            $data['alert'] = "Success";
            $data['message'] = "The new supplier has been inserted successfully !";
            echo json_encode($data);
            exit();
        }
            
	}

	if(isset($_POST['do']) && $_POST['do'] == "Delete")
	{
		$supplier_id = $_POST['supplier_id'];

        $stmt = $con->prepare("DELETE from supplier where supplier_id = ?");
        $stmt->execute(array($supplier_id));    
	}
	
?>