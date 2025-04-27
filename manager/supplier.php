<?php
    ob_start();
	session_start();

	$pageTitle = 'Suppliers';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        ?>

            <script type="text/javascript">

                var vertical_supplier = document.getElementById("vertical-supplier");


                var current = vertical_supplier.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_supplier.getElementsByClassName('supplier  _link')[0].className += " active_link";

            </script>

            <style type="text/css">

                .supplier-table
                {
                    -webkit-box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    text-align: center;
                    vertical-align: middle;
                }

            </style>

        <?php
            
            $stmt = $con->prepare("SELECT * FROM supplier");
            $stmt->execute();
            $supplier = $stmt->fetchAll();

        ?>
            <div class="card">
                <div class="card-header">
                    <?php echo $pageTitle; ?>
                </div>
                <div class="card-body">

                	<!-- ADD NEW SUPPLIER BUTTON -->

                	<button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_supplier" data-placement="top">
                    	<i class="fa fa-plus"></i> 
                    	Add Supplier
                	</button>

                    <!-- Add New supplier Modal -->

                    <div class="modal fade" id="add_new_supplier" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Supplier</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="supplier_name">Supplier name</label>
                                        <input type="text" id="supplier_name_input" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" placeholder="Supplier Name" name="supplier_name">
                                        <div id = 'required_supplier_name' class="invalid-feedback">
                                            <div>Supplier name is required!</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="supplier_number">Supplier Number</label>
                                        <input type="text" id="supplier_number_input" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z0-9]/g,'');" placeholder="Supplier Number" name="supplier_number">
                                        <div id = 'required_supplier_number' class="invalid-feedback">
                                            <div>Supplier number is required!</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-info" id="add_supplier_bttn">Add Supplier</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- SUPPLIER TABLE -->

                    <table class="table table-bordered categories-table">
                        <thead>
                            <tr>
                                <th scope="col">Supplier ID</th>
                                <th scope="col">Supplier Name</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($supplier as $supplier)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $supplier['supplier_id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $supplier['supplier_name'];
                                        echo "</td>";
                                        echo "<td style = 'text-transform:capitalize'>";
                                            echo $supplier['supplier_number'];
                                        echo "</td>";
                                        echo "<td>";
                                            /****/
                                                $delete_data = "delete_".$supplier["supplier_id"];
                                                ?>
                                                    <ul class="list-inline m-0">

                                                        <!-- DELETE BUTTON -->

                                                        <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                            <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top">
                                                            	<i class="fa fa-trash"></i>
                                                            </button>

                                                            <!-- Delete Modal -->

		                                                    <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
		                                                        <div class="modal-dialog" role="document">
		                                                            <div class="modal-content">
		                                                                <div class="modal-header">
		                                                                    <h5 class="modal-title">Delete Supplier</h5>
		                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                                                                        <span aria-hidden="true">&times;</span>
		                                                                    </button>
		                                                                </div>
		                                                                <div class="modal-body">
		                                                                    Are you sure you want to delete this Supplier "<?php echo strtoupper($supplier['supplier_name']); ?>"?
		                                                                </div>
		                                                                <div class="modal-footer">
		                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		                                                                    <button type="button" data-id = "<?php echo $supplier['supplier_id']; ?>" class="btn btn-danger delete_supplier_bttn">Delete</button>
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

        /*** FOOTER BOTTON ***/

        include 'Includes/templates/footer.php';

    }
    else
    {
        header('Location: index.php');
        exit();
    }

?>

<!-- JS SCRIPTS -->

<script type="text/javascript">


	// When add supplier button is clicked

    $('#add_supplier_bttn').click(function()
    {
        var supplier_name = $("#supplier_name_input").val();
        var supplier_number = $("#supplier_number_input").val();
        var do_ = "Add";

        if($.trim(supplier_name) == "")
        {
            $('#required_supplier_name').css('display','block');
        }
        else
        {
            $.ajax(
            {
                url:"ajax_files/supplier_ajax.php",
                method:"POST",
                data:{supplier_name:supplier_name,supplier_number:supplier_number,do:do_},
                dataType:"JSON",
                success: function (data) 
                {
                    if(data['alert'] == "Warning")
                    {
                        swal("Warning",data['message'], "warning").then((value) => {});
                    }
                    if(data['alert'] == "Success")
                    {
                        swal("New Supplier",data['message'], "success").then((value) => {
                            window.location.replace("supplier.php");
                        });
                    }
                    
                },
                error: function(xhr, status, error) 
                {
                    alert('AN ERROR HAS BEEN ENCOUNTERED WHILE TRYING TO EXECUTE YOUR REQUEST');
                }
            });
        }
    });

	// When delete Supplier button is clicked

    $('.delete_supplier_bttn').click(function()
    {
        var supplier_id = $(this).data('id');
        var do_ = "Delete";

        $.ajax(
        {
            url:"ajax_files/supplier_ajax.php",
            method:"POST",
            data:{supplier_id:supplier_id,do:do_},
            success: function (data) 
            {
                swal("Delete Supplier","The supplier has been deleted successfully!", "success").then((value) => {
                    window.location.replace("supplier.php");
                });     
            },
            error: function(xhr, status, error) 
            {
                alert('AN ERROR HAS BEEN ENCOUNTERED WHILE TRYING TO EXECUTE YOUR REQUEST');
            }
          });
    });

</script>

