<?php
    ob_start();
	session_start();

	$pageTitle = 'Profile';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        ?>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script type="text/javascript">

                var vertical_menu = document.getElementById("vertical-menu");


                var current = vertical_menu.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_menu.getElementsByClassName('users_link')[0].className += " active_link";

            </script>

        <?php
            $do = '';

            if(isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add','Edit')))
                $do = $_GET['do'];
            else
                $do = 'Edit';

            if($do == "Manage")
            {
                $user_id = (isset($_GET['user_id']) && is_numeric($_GET['user_id']))?intval($_GET['user_id']):0;
                $stmt = $con->prepare("SELECT * FROM users WHERE user_id=?");
                $stmt->execute(array($user_id));
                $users = $stmt->fetchAll();
    
                ?>
                    <div class="card">
                        <div class="card-header">
                            <?php echo $pageTitle; ?>
                        </div>
                        <div class="card-body">
    
                            <!-- MANAGER TABLE -->
    
                            <table class="table table-bordered staff-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Number</th>
                                        <th scope="col">Manage</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($users as $users)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo $users['username'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $users['email'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $users['user_number'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<button class='btn btn-success btn-sm rounded-0'>";
                                                        echo "<a href='users.php?do=Edit&user_id=".$users['user_id']."' style='color: white;'";
                                                        echo "<i class='fa fa-edit'></i>";
                                                        echo "</a>";
                                                    echo "</button>";
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                <?php
                }

            
            # Edit the staff details
            elseif($do == 'Edit')
            {
                $user_id = (isset($_GET['user_id']) && is_numeric($_GET['user_id']))?intval($_GET['user_id']):0;
                
                if($user_id)
                {
                    $stmt = $con->prepare("Select * from users where user_id = ?");
                    $stmt->execute(array($user_id));
                    $users = $stmt->fetch();
                    $count = $stmt->rowCount();
                    if($count > 0)
                    {
                        ?>

                        <div class="card">
                            <div class="card-header">
                                Edit Profile
                            </div>
                            <div class="card-body">
                                <form method="POST" class="menu_form" action="users.php?do=Edit&user_id=<?php echo $users['user_id'] ?>">
                                    <div class="panel-X">
                                        <div class="panel-header-X">
                                            <div class="main-title">
                                                <?php echo $users['username']; ?>
                                            </div>
                                        </div>
                                        <div class="save-header-X">
                                            <div style="display:flex">
                                                <div class="icon">
                                                    <i class="fa fa-sliders-h"></i>
                                                </div>
                                                <div class="title-container">Details</div>
                                            </div>
                                            <div class="button-controls">
                                                <button type="submit" name="edit_user_sbmt" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                        <div class="panel-body-X">
                                                
                                            <!-- User ID -->

                                            <input type="hidden" name="user_id" value="<?php echo $users['user_id'];?>" >

                                            <!-- Staff_Name INPUT -->

                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" value="<?php echo $users['username'] ?>" placeholder="Username" name="user_name">
                                                <?php
                                                    $flag_edit_user_form = 0;

                                                    if(isset($_POST['edit_user_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['user_name'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Username is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>


                                            <!-- Staff Number INPUT -->
                                            <div class="form-group">
                                                <label for="user_number">Number</label>
                                                <input type="text" class="form-control" value="<?php echo $users['user_number'] ?>" placeholder="(000)-000-000" name="user_number">
                                                
                                                <?php

                                                    if(isset($_POST['edit_user_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['user_number'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Number is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                        elseif(!filter_var($_POST['user_number'], FILTER_VALIDATE_EMAIL))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Invalid number.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                                                                    
                                            <!-- Staff Email INPUT -->

                                            <div class="form-group">
                                                <label for="email">E-mail</label>
                                                <input type="email" class="form-control" value="<?php echo $users['email'] ?>" placeholder="User Email" name="email">
                                                <?php

                                                    if(isset($_POST['edit_user_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['email'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    E-mail is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Invalid e-mail.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>


                                            <!-- Staff_Password INPUT -->

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" name="password">
                                                <?php

                                                    if(isset($_POST['edit_user_sbmt']))
                                                    {
                                                        if(!empty($_POST['password']) and strlen($_POST['password']) < 8)
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Password length must be at least 8 characters.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php

                        /*** EDIT DETAILS ***/

                        if(isset($_POST['edit_user_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_user_form == 0)
                        {
                            $user_id = test_input($_POST['user_id']);
                            $user_name = test_input($_POST['user_name']);
                            $user_number = test_input($_POST['user_number']);
                            $email = test_input($_POST['email']);
                            $password = $_POST['password'];
                            $hashedPass = sha1($password);

                            if(empty($password))
                            {
                                try
                                {
                                    $stmt = $con->prepare("update users  set username = ?, user_number = ?, email = ? where user_id = ? ");
                                    $stmt->execute(array($user_name,$user_number,$email,$user_id));
                                    
                                    ?> 
                                        <!-- SUCCESS MESSAGE -->

                                        <script type="text/javascript">
                                            swal("Edit User","User has been updated successfully", "success").then((value) => 
                                            {
                                                window.location.replace("users.php");
                                            });
                                        </script>

                                    <?php

                                }
                                catch(Exception $e)
                                {
                                    echo 'Error occurred: ' .$e->getMessage();
                                }
                            }
                            else
                            {
                                $password = sha1($password);
                                try
                                {
                                    $stmt = $con->prepare("update users  set username = ?, user_number = ?, email = ?, password = ? where user_id = ? ");
                                    $stmt->execute(array($user_name,$user_number,$email,$password,$user_id));
                                    
                                    ?> 
                                        <!-- SUCCESS MESSAGE -->

                                        <script type="text/javascript">
                                            swal("Edit User","User has been updated successfully", "success").then((value) => 
                                            {
                                                window.location.replace("users.php");
                                            });
                                        </script>

                                    <?php

                                }
                                catch(Exception $e)
                                {
                                    echo 'Error occurred: ' .$e->getMessage();
                                }
                            }
                        }

                    }
                    else
                    {
                        header('Location: dashboard.php');
                    }
                }
                else
                {
                    header('Location: dashboard.php');
                }
            }


        /* FOOTER BOTTOM */

        include 'Includes/templates/footer.php';

    }
    else
    {
        header('Location: index.php');
        exit();
    }
?>