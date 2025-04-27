<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>

<?php

	if(isset($_POST['do_']) && $_POST['do_'] == "Delete")
	{
		$user_id = $_POST['user_id'];

        $stmt = $con->prepare("DELETE from users where user_id = ?");
        $stmt->execute(array($user_id));
	}

?>