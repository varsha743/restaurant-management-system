<?php
    ob_start();
	session_start();

	$pageTitle = 'Manager';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        ?>

            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

            <script type="text/javascript">

                var vertical_manager = document.getElementById("vertical-menu");


                var current = vertical_manager.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_manager.getElementsByClassName('manager_link')[0].className += " active_link";

            </script>

            <style type="text/css">

                .manager-table
                {
                    -webkit-box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                }

                .thumbnail>img 
                {
                    width: 100%;
                    object-fit: cover;
                    height: 300px;
                }

                .thumbnail .caption 
                {
                    padding: 9px;
                    color: #333;
                }

                .manager_form
                {
                    max-width: 750px;
                    margin:auto;
                }

                .panel-X
                {
                    border: 0;
                    -webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                    box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                    border-radius: .25rem;
                    position: relative;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    min-width: 0;
                    word-wrap: break-word;
                    background-color: #fff;
                    background-clip: border-box;
                    margin: auto;
                    width: 600px;
                }

                .panel-header-X 
                {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    padding-left: 1.25rem;
                    padding-right: 1.25rem;
                    border-bottom: 1px solid rgb(226, 226, 226);
                }

                .save-header-X 
                {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                    min-height: 65px;
                    padding: 0 1.25rem;
                    background-color: #f1fafd;
                }

                .panel-header-X>.main-title 
                {
                    font-size: 18px;
                    font-weight: 600;
                    color: #313e54;
                    padding: 15px 0;
                }

                .panel-body-X
                {
                    padding: 1rem 1.25rem;
                }

                .save-header-X .icon
                {
                    width: 20px;
                    text-align: center;
                    font-size: 20px;
                    color: #5b6e84;
                    margin-right: 1.25rem;
                }
            </style>

        <?php

            $do = '';

            if(isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add','Edit')))
                $do = $_GET['do'];
            else
                $do = 'Manage';

            if($do == "Manage")
            {
                $user_role="manager";
                $stmt = $con->prepare("SELECT * FROM users WHERE user_role=?");
                $stmt->execute(array($user_role));
                $users = $stmt->fetchAll();

            ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $pageTitle; ?>
                    </div>
                    <div class="card-body">

                        <!-- ADD NEW MANAGER ITEM BUTTON -->

                        <div class="above-table" style="margin-bottom: 1rem!important;">
                            <a href="manager.php?do=Add" class="btn btn-success">
                                <i class="fa fa-plus"></i> 
                                <span>Add new manager</span>
                            </a>
                        </div>

                        <!-- MANAGER TABLE -->

                        <table class="table table-bordered manager-table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone Number</th>
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
                                                /****/
                                                    $delete_data = "delete_".$users["user_id"];
                                                    ?>
                                                        <ul class="list-inline m-0">

                                                            <!-- EDIT BUTTON -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                                                <button class="btn btn-success btn-sm rounded-0">
                                                                    <a href="manager.php?do=Edit&user_id=<?php echo $users['user_id']; ?>" style="color: white;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </button>
                                                            </li>

                                                            <!-- DELETE BUTTON -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                                <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i>
                                                                </button>

                                                                <!-- Delete Modal -->

                                                                <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Delete manager details</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Are you sure you want to delete the manager details of"<?php echo strtoupper($users['username']); ?>"?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                <button type="button" data-id = "<?php echo $users['user_id']; ?>" class="btn btn-danger delete_manager_bttn">Delete</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    <?php
                                                /****/
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

            /*** ADD NEW MANAGER ***/

            elseif($do == 'Add')
            {
                ?>

                    <div class="card">
                        <div class="card-header">
                            Add New Manager
                        </div>
                        <div class="card-body">
                            <form method="POST" class="manager_form" action="manager.php?do=Add" enctype="multipart/form-data">
                                <div class="panel-X">
                                    <div class="panel-header-X">
                                        <div class="main-title">
                                            Add New Manager
                                        </div>
                                    </div>
                                    <div class="save-header-X">
                                        <div style="display:flex">
                                            <div class="icon">
                                                <i class="fa fa-sliders-h"></i>
                                            </div>
                                            <div class="title-container">Manager details</div>
                                        </div>
                                        <div class="button-controls">
                                            <button type="submit" name="add_new_manager" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <div class="panel-body-X">

                                        <!-- MANAGER NAME INPUT -->

                                        <div class="form-group">
                                            <label for="username">Manager Name</label>
                                            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z()_(0-9)]/g,'');" value="<?php echo (isset($_POST['username']))?htmlspecialchars($_POST['username']):'' ?>" placeholder="Manager Name" name="username">
                                            <?php
                                                $flag_add_manager_form = 0;

                                                if(isset($_POST['add_new_manager']))
                                                {
                                                    if(empty(test_input($_POST['username'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Manager name is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_manager_form = 1;
                                                    }
                                                    
                                                }
                                            ?>
                                        </div>

                                        
                                    <!-- MANAGER EMAIL INPUT -->

                                        <div class="form-group">
                                            <label for="email">Manager Email</label>
                                            <input type="email" class="form-control" value="<?php echo (isset($_POST['email']))?htmlspecialchars($_POST['email']):'' ?>" placeholder="manager_email" name="email">
                                            <?php

                                                if(isset($_POST['add_new_manager']))
                                                {
                                                    if(empty(test_input($_POST['email'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Manager Email is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_manager_form = 1;
                                                    }
                                                    
                                                    
                                                }
                                            ?>
                                        </div>
                                        <!-- MANAGER NUMBER INPUT -->

                                        <div class="form-group">
                                            <label for="user_number">Manager Number</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['user_number']))?htmlspecialchars($_POST['user_number']):'' ?>" placeholder="000-000-0000" name="user_number">
                                            <?php
                                                if(isset($_POST['add_new_manager']))
                                                {
                                                    if(empty(test_input($_POST['user_number'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Manager Number is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_manager_form = 1;
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

                /*** ADD NEW manager item ***/

                if(isset($_POST['add_new_manager']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_manager_form == 0)
                {
                    $username = test_input($_POST['username']);
                    $email = test_input($_POST['email']);
                    $user_number = test_input($_POST['user_number']);
                    $user_role = "manager";

                    try
                    {
                        $stmt = $con->prepare("insert into users(username,email,user_number,user_role) values(?,?,?,?) ");
                        $stmt->execute(array($username,$email,$user_number,$user_role));
                        
                        ?> 
                            <!-- SUCCESS MESSAGE -->

                            <script type="text/javascript">
                                swal("New Manager","The new manager details has been inserted successfully", "success").then((value) => 
                                {
                                    window.location.replace("manager.php");
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
                                Edit Manager
                            </div>
                            <div class="card-body">
                                <form method="POST" class="manager_form" action="manager.php?do=Edit&user_id=<?php echo $users['user_id'] ?>" enctype="multipart/form-data">
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
                                                <div class="title-container">Manager details</div>
                                            </div>
                                            <div class="button-controls">
                                                <button type="submit" name="edit_manager_sbmt" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                        <div class="panel-body-X">
                                                
                                            <!-- MANAGER ID -->

                                            <input type="hidden" name="user_id" value="<?php echo $users['user_id'];?>" >

                                            <!-- MANAGER NAME INPUT -->

                                            <div class="form-group">
                                                <label for="username">Manager Name</label>
                                                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z()]/g,'');" value="<?php echo $users['username'] ?>" placeholder="Manager Name" name="username">
                                                <?php
                                                    $flag_edit_manager_form = 0;

                                                    if(isset($_POST['edit_manager_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['username'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Manager Name is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_manager_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        
                                            
                                            <!-- MANAGER EMAIL INPUT -->

                                            <div class="form-group">
                                                <label for="email">Manager Email</label>
                                                <input type="email" class="form-control" value="<?php echo $users['email'] ?>" placeholder="Manager email" name="email">
                                                <?php

                                                    if(isset($_POST['edit_manager_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['email'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Manager Email is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_manager_form = 1;
                                                        }
                                                
                                                    }
                                                ?>
                                            </div>
                                            <!-- MANAGER NUMBER INPUT -->

                                            <div class="form-group">
                                                <label for="user_number">Manager Number</label>
                                                <input type="text" class="form-control" value="<?php echo $users['user_number'] ?>" placeholder="000-000-0000" name="user_number">
                                                <?php

                                                    if(isset($_POST['edit_manager_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['user_number'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Manager Number is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_manager_form = 1;
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

                        /*** EDIT manager ***/

                        if(isset($_POST['edit_manager_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_manager_form == 0)
                        {
                            $user_id = test_input($_POST['user_id']);
                            $username = test_input($_POST['username']);
                            $email = test_input($_POST['email']);
                            $user_number = test_input($_POST['user_number']);
                            if(TRUE)
                            {
                                try
                                {
                                    $stmt = $con->prepare("update users  set username = ?, email = ?, user_number = ? where user_id = ? ");
                                    $stmt->execute(array($username,$email,$user_number,$user_id));
                                    
                                    ?> 
                                        <!-- SUCCESS MESSAGE -->

                                        <script type="text/javascript">
                                            swal("Edit Manager Details","Manager Details have been updated successfully", "success").then((value) => 
                                            {
                                                window.location.replace("manager.php");
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
                        header('Location: manager.php');
                    }
                }
                else
                {
                    header('Location: manager.php');
                }
            }


        /*** FOOTER BOTTON ***/

        include 'Includes/templates/footer.php';

    }
    else
    {
        header('Location: index.php');
        exit();
    }

?>

<!-- JS SCRIPT -->

<script type="text/javascript">

    // When delete manager button is clicked

    $('.delete_manager_bttn').click(function()
    {
        var user_id = $(this).data('id');
        var do_ = "Delete";

        $.ajax(
        {
            url:"ajax_files/manager_ajax.php",
            method:"POST",
            data:{user_id:user_id,do_:do_},
            success: function (data) 
            {
                swal("Delete Manager Details","Manager Details have been deleted successfully!", "success").then((value) => {
                    window.location.replace("manager.php");
                });     
            },
            error: function(xhr, status, error) 
            {
                alert('AN ERROR HAS BEEN ENCOUNTERED WHILE TRYING TO EXECUTE YOUR REQUEST');
            }
          });
    });


</script>
