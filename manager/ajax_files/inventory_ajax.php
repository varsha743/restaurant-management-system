<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>

<?php

	if(isset($_POST['do_']) && $_POST['do_'] == "Delete")
	{
		$grocery_id = $_POST['grocery_id'];

        $stmt = $con->prepare("DELETE from inventory where grocery_id = ?");
        $stmt->execute(array($grocery_id));
	}

?>