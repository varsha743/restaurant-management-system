<?php
	
	//Start session
    session_start();

    //Set page title
    $pageTitle = 'Dashboard';

    //PHP INCLUDES
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //TEST IF THE SESSION HAS BEEN CREATED BEFORE

    if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
    {
    	include 'Includes/templates/navbar.php';

    	?>

            <script type="text/javascript">

                var vertical_menu = document.getElementById("vertical-menu");


                var current = vertical_menu.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_menu.getElementsByClassName('dashboard_link')[0].className += " active_link";

            </script>

            <!-- TOP 4 CARDS -->

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="panel panel-green ">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fas fa-tachometer-alt fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span></span></div>
                                    <div>Analytics</div>
                                </div>
                            </div>
                        </div>
                        <a href=#>
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="far fa-address-book fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span></span></div>
                                    <div>Staff</div>
                                </div>
                            </div>
                        </div>
                        <a href="staff.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class=" col-sm-6 col-lg-3">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="far fa-address-card fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span></span></div>
                                    <div>Manager</div>
                                </div>
                            </div>
                        </div>
                        <a href="manager.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class=" col-sm-6 col-lg-3">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fa fa-cogs fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span></span></div>
                                    <div>Website-Settings</div>
                                </div>
                            </div>
                        </div>
                        <a href="website-settings.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <img src="Uploads/images/dashboard.jpg" alt="Dashboard Picture" class="img-fluid">
                </div>
            </div>

            
        <?php

    	include 'Includes/templates/footer.php';

    }
    else
    {
    	header("Location: index.php");
    	exit();
    }

?>


</script>