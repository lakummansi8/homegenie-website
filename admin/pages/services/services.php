<?php 
$pageTitle = "Services";  
$assetPath = "../../../";  
$adminPath = "../../";  

require_once "../../../config/db.php";  
  
$q = "select * from services";  
$res = mysqli_query($conn,$q);  

$totalServices = mysqli_num_rows($res);
  
ob_start();  
?>  
  
<div class="admin-page-header">  

    <div class="header-title">  
        <h3>Services</h3>  
        <p class="text-muted">
            Manage the services available on HomeGenie.
        </p>  
    </div>  

    <div class="header-actions">  
        <a href="add-service.php" class="btn btn-primary">
            + Add Service
        </a>  
    </div>  

</div>  
  
<?php if (isset($_GET["success"])): ?>  

    <div class="alert alert-success">  

        <?php   
            if ($_GET["success"] === "service_added")
            {
                echo "Service added successfully.";
            }
            elseif ($_GET["success"] === "service_updated")
            {
                echo "Service updated successfully.";
            }
            elseif ($_GET["success"] === "service_deleted")
            {
                echo "Service deleted successfully.";
            }
        ?>  

    </div>  

<?php endif; ?>  
  
<?php if (isset($_GET["error"])): ?>  

    <div class="alert alert-danger">  

        <?php

            if ($_GET["error"] === "invalid_service")
            {
                echo "Invalid service.";
            }
            elseif ($_GET["error"] === "service_not_found")
            {
                echo "Service not found.";
            }
            else
            {
                echo "Something went wrong.";
            }

        ?>  

    </div>  

<?php endif; ?>  
  
<div class="card services-card">  

    <div class="card-header bg-dark text-white services-card-header">  

        <strong>All Services</strong>

        <span class="service-count">
            <?php print $totalServices; ?> Services
        </span>

    </div>  


    <div class="card-body">  

        <?php if ($totalServices == 0): ?>  

            <p class="text-muted">
                No services found. Create your first service to get started.
            </p>  

            <a href="add-service.php" class="btn btn-primary">
                + Add Service
            </a>  

        <?php else: ?>  

            <div class="table-responsive">  

                <table class="table table-bordered table-striped services-table">  

                    <thead class="table-light">  

                        <tr>  

                            <th>Sr. No.</th>  
                            <th>Service</th>  
                            <th>Category</th>  
                            <th>Provider</th>  
                            <th>Description</th>  
                            <th>Price / Hour</th>  
                            <th>Status</th>  
                            <th>Created</th>  
                            <th>Actions</th>  

                        </tr>  

                    </thead>  


                    <tbody>  

                        <?php 
                        $srno = 1;
                        ?>

                        <?php while($service = mysqli_fetch_array($res)) { ?>  

                            <?php 

                            $categoryId = $service['category_id']; 

                            $q1 = "select * from categories where category_id = $categoryId"; 
                            $res1 = mysqli_query($conn,$q1); 
                            $category = mysqli_fetch_array($res1); 


                            $providerId = $service['provider_id']; 

                            $q2 = "select * from service_providers where provider_id = $providerId"; 
                            $res2 = mysqli_query($conn,$q2); 
                            $provider = mysqli_fetch_array($res2); 

                            ?> 


                            <tr>  


                                <!-- SERIAL NUMBER -->

                                <td class="service-id">

                                    <?php print $srno; ?>

                                </td>


                                <!-- SERVICE -->

                                <td>

                                    <div class="service-name">

                                        <?php if ($service['service_image'] != "") { ?>

                                            <img
                                                src="../../../assets/services/<?php print $service['service_image']; ?>"
                                                alt="<?php print $service['service_name']; ?>"
                                                style="
                                                    width: 55px !important;
                                                    height: 45px !important;
                                                    min-width: 55px !important;
                                                    max-width: 55px !important;
                                                    min-height: 45px !important;
                                                    max-height: 45px !important;
                                                    object-fit: cover !important;
                                                    display: block !important;
                                                    flex: 0 0 55px !important;
                                                    border-radius: 5px !important;
                                                    border: 1px solid #e5e7eb !important;
                                                    background: #f3f4f6 !important;
                                                "
                                            >

                                        <?php } else { ?>

                                            <div
                                                style="
                                                    width: 55px;
                                                    height: 45px;
                                                    min-width: 55px;
                                                    max-width: 55px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    flex: 0 0 55px;
                                                    border: 1px solid #e5e7eb;
                                                    border-radius: 5px;
                                                    color: #6b7280;
                                                    background: #f3f4f6;
                                                    font-size: 13px;
                                                "
                                            >
                                                -
                                            </div>

                                        <?php } ?>


                                        <div class="service-name-content">

                                            <strong>
                                                <?php print $service['service_name']; ?>
                                            </strong>

                                        </div>

                                    </div>

                                </td>


                                <!-- CATEGORY -->

                                <td class="service-category"> 

                                    <?php 

                                    if($category) 
                                    { 
                                        print $category['category_name']; 
                                    } 
                                    else 
                                    { 
                                        print "No category"; 
                                    } 

                                    ?> 

                                </td>


                                <!-- PROVIDER -->

                                <td class="service-provider"> 

                                    <?php 

                                    if($provider) 
                                    { 
                                        print $provider['full_name']; 
                                    } 
                                    else 
                                    { 
                                        print "No provider"; 
                                    } 

                                    ?> 

                                </td>


                                <!-- DESCRIPTION -->

                                <td class="service-description"> 

                                    <?php 

                                    if($service['description'] != "") 
                                    { 
                                        print $service['description']; 
                                    } 
                                    else 
                                    { 
                                        print "No description"; 
                                    } 

                                    ?> 

                                </td>


                                <!-- PRICE -->

                                <td>

                                    <span class="service-price">

                                        ₹<?php print number_format($service['price'],2); ?>

                                        <small>
                                            / hour
                                        </small>

                                    </span>

                                </td>


                                <!-- STATUS -->

                                <td>  

                                    <?php if($service['service_status'] == "Active") { ?>  

                                        <span class="badge bg-success">
                                            Active
                                        </span>  

                                    <?php } else { ?>  

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>  

                                    <?php } ?>  

                                </td>


                                <!-- CREATED -->

                                <td class="service-date">

                                    <?php 
                                        print date(
                                            "d M Y",
                                            strtotime($service['created_at'])
                                        ); 
                                    ?>

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="service-actions">

                                        <a
                                            href="edit-service.php?id=<?php print $service['service_id']; ?>"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Edit
                                        </a>  

                                        <a
                                            href="delete-service.php?id=<?php print $service['service_id']; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?');"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>  


                            </tr>


                            <?php $srno++; ?>


                        <?php } ?>  

                    </tbody>  

                </table>  

            </div>  

        <?php endif; ?>  

    </div>  

</div>  
  
<?php  
$pageContent = ob_get_clean();  
require_once "../../layout/admin-layout.php";  
?>