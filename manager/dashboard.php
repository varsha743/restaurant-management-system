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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_delivered'])) {
        $order_id = intval($_POST['order_id']);
    
        $stmt = $con->prepare("UPDATE placed_orders 
                               SET delivered = 1 
                               WHERE order_id = ? AND canceled = 0");
        $stmt->execute([$order_id]);
    
        header("Location: dashboard.php");
        exit();
    }

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
                                    <i class="fa fa-users fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("client_id","clients")?></span></div>
                                    <div>Clients</div>
                                </div>
                            </div>
                        </div>
                        <a href="clients.php">
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
                                    <i class="fas fa-pizza-slice fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("menu_id","menus")?></span></div>
                                    <div>Menu</div>
                                </div>
                            </div>
                        </div>
                        <a href="menus.php">
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
                                    <i class="fas fa-utensils fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("grocery_id","inventory")?></span></div>
                                    <div>Inventory</div>
                                </div>
                            </div>
                        </div>
                        <a href="inventory.php">
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
                                    <i class="fa fa-users fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("supplier_id","supplier")?></span></div>
                                    <div>Suppliers</div>
                                </div>
                            </div>
                        </div>
                        <a href="supplier.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
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