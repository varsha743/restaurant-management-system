<?php
    ob_start();
	session_start();

	$pageTitle = 'Inventory';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        ?>

            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

            <script type="text/javascript">

                var vertical_inventory = document.getElementById("vertical-menu");


                var current = vertical_inventory.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_inventory.getElementsByClassName('inventory_link')[0].className += " active_link";

            </script>

            <style type="text/css">

                .inventory-table
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

                .inventory_form
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
                $stmt = $con->prepare("SELECT * FROM inventory i, supplier s where s.supplier_id = i.supplier_id");
                $stmt->execute();
                $inventory = $stmt->fetchAll();

            ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $pageTitle; ?>
                    </div>
                    <div class="card-body">

                        <!-- ADD NEW INVENTORY ITEM BUTTON -->

                        <div class="above-table" style="margin-bottom: 1rem!important;">
                            <a href="inventory.php?do=Add" class="btn btn-success">
                                <i class="fa fa-plus"></i> 
                                <span>Add new inventory item</span>
                            </a>
                        </div>

                        <!-- INVENTORY TABLE -->

                        <table class="table table-bordered inventory-table">
                            <thead>
                                <tr>
                                    <th scope="col">Grocery Name</th>
                                    <th scope="col">Grocery Price</th>
                                    <th scope="col">Quantity Left</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($inventory as $inventory)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $inventory['grocery_name'];
                                            echo "</td>";
                                            echo "<td style = 'text-transform:capitalize'>";
                                                echo "$".$inventory['grocery_price'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $inventory['quantity_left'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $inventory['supplier_name'];
                                            echo "</td>";
                                            echo "<td>";
                                                /****/
                                                    $delete_data = "delete_".$inventory["grocery_id"];
                                                    ?>
                                                        <ul class="list-inline m-0">

                                                            <!-- EDIT BUTTON -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                                                <button class="btn btn-success btn-sm rounded-0">
                                                                    <a href="inventory.php?do=Edit&grocery_id=<?php echo $inventory['grocery_id']; ?>" style="color: white;">
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
                                                                                <h5 class="modal-title">Delete inventory item</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Are you sure you want to delete this inventory item "<?php echo strtoupper($inventory['grocery_name']); ?>"?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                <button type="button" data-id = "<?php echo $inventory['grocery_id']; ?>" class="btn btn-danger delete_grocery_bttn">Delete</button>
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

            /*** ADD NEW INVENTORY ITEM SCRIPT ***/

            elseif($do == 'Add')
            {
                ?>

                    <div class="card">
                        <div class="card-header">
                            Add New Inventory Item
                        </div>
                        <div class="card-body">
                            <form method="POST" class="inventory_form" action="inventory.php?do=Add" enctype="multipart/form-data">
                                <div class="panel-X">
                                    <div class="panel-header-X">
                                        <div class="main-title">
                                            Add New Grocery Item
                                        </div>
                                    </div>
                                    <div class="save-header-X">
                                        <div style="display:flex">
                                            <div class="icon">
                                                <i class="fa fa-sliders-h"></i>
                                            </div>
                                            <div class="title-container">Grocery details</div>
                                        </div>
                                        <div class="button-controls">
                                            <button type="submit" name="add_new_grocery" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <div class="panel-body-X">

                                        <!-- GROCERY NAME INPUT -->

                                        <div class="form-group">
                                            <label for="grocery_name">Grocery Name</label>
                                            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z()]/g,'');" value="<?php echo (isset($_POST['grocery_name']))?htmlspecialchars($_POST['grocery_name']):'' ?>" placeholder="Grocery Name" name="grocery_name">
                                            <?php
                                                $flag_add_inventory_form = 0;

                                                if(isset($_POST['add_new_grocery']))
                                                {
                                                    if(empty(test_input($_POST['grocery_name'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Grocery name is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_inventory_form = 1;
                                                    }
                                                    
                                                }
                                            ?>
                                        </div>

                                        
                                    <!-- GROCERY PRICE INPUT -->

                                        <div class="form-group">
                                            <label for="grocery_price">Grocery Price($)</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['grocery_price']))?htmlspecialchars($_POST['grocery_price']):'' ?>" placeholder="0.00" name="grocery_price">
                                            <?php

                                                if(isset($_POST['add_new_grocery']))
                                                {
                                                    if(empty(test_input($_POST['grocery_price'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Grocery price is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_inventory_form = 1;
                                                    }
                                                    elseif(!is_numeric(test_input($_POST['grocery_price'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Invalid price.
                                                            </div>
                                                        <?php

                                                        $flag_add_inventory_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <!-- QUNATITY LEFT INPUT -->

                                        <div class="form-group">
                                            <label for="quantity_left">Quantity Left</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['quantity_left']))?htmlspecialchars($_POST['quantity_left']):'' ?>" placeholder="0" name="quantity_left">
                                            <?php
                                                if(isset($_POST['add_new_grocery']))
                                                {
                                                    if(empty(test_input($_POST['quantity_left'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Quantity Left is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_inventory_form = 1;
                                                    }
                                                    elseif(!is_numeric(test_input($_POST['quantity_left'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Invalid Quantity.
                                                            </div>
                                                        <?php

                                                        $flag_add_inventory_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>


                                        <!-- SUPPLIER INPUT -->
                                        <div class="form-group">
                                            <?php
                                                $stmt = $con->prepare("SELECT * FROM supplier");
                                                $stmt->execute();
                                                $rows_supplier = $stmt->fetchAll();
                                            ?>
                                            <label for="supplier">Supplier Name</label>
                                            <select class="custom-select" name="supplier">
                                                <?php
                                                    foreach($rows_supplier as $supplier)
                                                    {
                                                        echo "<option value = '".$supplier['supplier_id']."'>";
                                                            echo ucfirst($supplier['supplier_name']);
                                                        echo "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php

                /*** ADD NEW grocery item ***/

                if(isset($_POST['add_new_grocery']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_inventory_form == 0)
                {
                    $grocery_name = test_input($_POST['grocery_name']);
                    $grocery_price = test_input($_POST['grocery_price']);
                    $quantity_left = test_input($_POST['quantity_left']);
                    $supplier = $_POST['supplier'];

                    try
                    {
                        $stmt = $con->prepare("insert into inventory(grocery_name,grocery_price,quantity_left,supplier_id) values(?,?,?,?) ");
                        $stmt->execute(array($grocery_name,$grocery_price,$quantity_left,$supplier));
                        
                        ?> 
                            <!-- SUCCESS MESSAGE -->

                            <script type="text/javascript">
                                swal("New grocery","The new grocery item has been inserted successfully", "success").then((value) => 
                                {
                                    window.location.replace("inventory.php");
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
                $grocery_id = (isset($_GET['grocery_id']) && is_numeric($_GET['grocery_id']))?intval($_GET['grocery_id']):0;

                if($grocery_id)
                {
                    $stmt = $con->prepare("Select * from inventory where grocery_id = ?");
                    $stmt->execute(array($grocery_id));
                    $inventory = $stmt->fetch();
                    $count = $stmt->rowCount();

                    if($count > 0)
                    {
                        ?>

                        <div class="card">
                            <div class="card-header">
                                Edit Inventory
                            </div>
                            <div class="card-body">
                                <form method="POST" class="inventory_form" action="inventory.php?do=Edit&grocery_id=<?php echo $inventory['grocery_id'] ?>" enctype="multipart/form-data">
                                    <div class="panel-X">
                                        <div class="panel-header-X">
                                            <div class="main-title">
                                                <?php echo $inventory['grocery_name']; ?>
                                            </div>
                                        </div>
                                        <div class="save-header-X">
                                            <div style="display:flex">
                                                <div class="icon">
                                                    <i class="fa fa-sliders-h"></i>
                                                </div>
                                                <div class="title-container">Grocery details</div>
                                            </div>
                                            <div class="button-controls">
                                                <button type="submit" name="edit_inventory_sbmt" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                        <div class="panel-body-X">
                                                
                                            <!-- GROCERY ID -->

                                            <input type="hidden" name="grocery_id" value="<?php echo $inventory['grocery_id'];?>" >

                                            <!-- GROCERY NAME INPUT -->

                                            <div class="form-group">
                                                <label for="grocery_name">Grocery Name</label>
                                                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z()]/g,'');" value="<?php echo $inventory['grocery_name'] ?>" placeholder="Grocery Name" name="grocery_name">
                                                <?php
                                                    $flag_edit_inventory_form = 0;

                                                    if(isset($_POST['edit_inventory_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['grocery_name'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Grocery Name is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_inventory_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        
                                            
                                            <!-- GROCERY PRICE INPUT -->

                                            <div class="form-group">
                                                <label for="grocery_price">Grocery Price($)</label>
                                                <input type="text" class="form-control" value="<?php echo $inventory['grocery_price'] ?>" placeholder="0.00" name="grocery_price">
                                                <?php

                                                    if(isset($_POST['edit_inventory_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['grocery_price'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Grocery Price is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_inventory_form = 1;
                                                        }
                                                        elseif(!is_numeric(test_input($_POST['grocery_price'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Invalid price.
                                                                </div>
                                                            <?php

                                                            $flag_edit_inventory_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <!-- QUANTITY LEFT INPUT -->

                                            <div class="form-group">
                                                <label for="quantity_left">Quantity Left</label>
                                                <input type="text" class="form-control" value="<?php echo $inventory['quantity_left'] ?>" placeholder="0" name="quantity_left">
                                                <?php

                                                    if(isset($_POST['edit_inventory_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['quantity_left'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Quantity Price is required.
                                                                </div>
                                                            <?php

                                                            $flag_edit_inventory_form = 1;
                                                        }
                                                        elseif(!is_numeric(test_input($_POST['quantity_left'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Invalid price.
                                                                </div>
                                                            <?php

                                                            $flag_edit_inventory_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <!-- SUPPLIER INPUT -->

                                            <div class="form-group">
                                                <?php
                                                    $stmt = $con->prepare("SELECT * FROM supplier");
                                                    $stmt->execute();
                                                    $rows_supplier = $stmt->fetchAll();
                                                ?>
                                                <label for="supplier">Supplier Name</label>
                                                <select class="custom-select" name="supplier">
                                                    <?php
                                                        foreach($rows_supplier as $supplier)
                                                        {
                                                            if($supplier['supplier_id'] == $inventory['supplier_id'])
                                                            {
                                                                echo "<option value = '".$supplier['supplier_id']."' selected>";
                                                                    echo ucfirst($supplier['supplier_name']);
                                                                echo "</option>";
                                                            }
                                                            else
                                                            {
                                                                echo "<option value = '".$supplier['supplier_id']."'>";
                                                                    echo ucfirst($supplier['supplier_name']);
                                                                echo "</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php

                        /*** EDIT INVENTORY ***/

                        if(isset($_POST['edit_inventory_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_inventory_form == 0)
                        {
                            $grocery_id = test_input($_POST['grocery_id']);
                            $grocery_name = test_input($_POST['grocery_name']);
                            $grocery_price = test_input($_POST['grocery_price']);
                            $quantity_left = test_input($_POST['quantity_left']);
                            $supplier = $_POST['supplier'];
                            if(TRUE)
                            {
                                try
                                {
                                    $stmt = $con->prepare("update inventory  set grocery_name = ?, grocery_price = ?, quantity_left = ?, supplier_id = ? where grocery_id = ? ");
                                    $stmt->execute(array($grocery_name,$grocery_price,$quantity_left,$supplier,$grocery_id));
                                    
                                    ?> 
                                        <!-- SUCCESS MESSAGE -->

                                        <script type="text/javascript">
                                            swal("Edit Menu","Menu has been updated successfully", "success").then((value) => 
                                            {
                                                window.location.replace("inventory.php");
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
                        header('Location: inventory.php');
                    }
                }
                else
                {
                    header('Location: inventory.php');
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

    // When delete grocery button is clicked

    $('.delete_grocery_bttn').click(function()
    {
        var grocery_id = $(this).data('id');
        var do_ = "Delete";

        $.ajax(
        {
            url:"ajax_files/inventory_ajax.php",
            method:"POST",
            data:{grocery_id:grocery_id,do_:do_},
            success: function (data) 
            {
                swal("Delete Grocery Item","The Grocery item has been deleted successfully!", "success").then((value) => {
                    window.location.replace("inventory.php");
                });     
            },
            error: function(xhr, status, error) 
            {
                alert('AN ERROR HAS BEEN ENCOUNTERED WHILE TRYING TO EXECUTE YOUR REQUEST');
            }
          });
    });


</script>
