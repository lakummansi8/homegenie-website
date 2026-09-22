<?php 
$pageTitle = "Services"; 
$assetPath = "../../../"; 
$adminPath = "../../"; 
require_once "../../../config/db.php"; 
 
$q = "select * from services"; 
$res = mysqli_query($conn,$q); 
 
ob_start(); 
?> 
 
<div class="admin-page-header"> 
    <div class="header-title"> 
        <h3>Services</h3> 
        <p class="text-muted">Manage the services available on HomeGenie.</p> 
    </div> 
    <div class="header-actions"> 
        <a href="add-service.php" class="btn btn-primary">+ Add Service</a> 
    </div> 
</div> 
 
<?php if (isset($_GET["success"])): ?> 
    <div class="alert alert-success"> 
        <?php  
            if ($_GET["success"] === "service_added") echo "Service added successfully."; 
            elseif ($_GET["success"] === "service_updated") echo "Service updated successfully."; 
            elseif ($_GET["success"] === "service_deleted") echo "Service deleted successfully."; 
        ?> 
    </div> 
<?php endif; ?> 
 
<?php if (isset($_GET["error"])): ?> 
    <div class="alert alert-danger"> 
        <?php 
            if ($_GET["error"] === "invalid_service") echo "Invalid service."; 
            elseif ($_GET["error"] === "service_not_found") echo "Service not found."; 
            else echo "Something went wrong."; 
        ?> 
    </div> 
<?php endif; ?> 
 
<div class="card"> 
    <div class="card-header bg-dark text-white"> 
        <strong>All Services</strong> 
    </div> 
    <div class="card-body"> 
        <?php if (mysqli_num_rows($res) == 0): ?> 
            <p class="text-muted">No services found. Create your first service to get started.</p> 
            <a href="add-service.php" class="btn btn-primary">+ Add Service</a> 
        <?php else: ?> 
            <div class="table-responsive"> 
                <table class="table table-bordered table-striped"> 
                    <thead class="table-light"> 
                        <tr> 
                            <th>ID</th> 
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
                                <td><?php print $service['service_id']; ?></td> 
                                <td><strong><?php print $service['service_name']; ?></strong></td> 
                                <td>
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
                                <td>
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
                                <td>
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
                                <td>₹<?php print number_format($service['price'],2); ?> <small>/ hour</small></td> 
                                <td> 
                                    <?php if($service['service_status'] == "Active") { ?> 
                                        <span class="badge bg-success">Active</span> 
                                    <?php } else { ?> 
                                        <span class="badge bg-secondary">Inactive</span> 
                                    <?php } ?> 
                                </td> 
                                <td><?php print $service['created_at']; ?></td> 
                                <td> 
                                    <a href="edit-service.php?id=<?php print $service['service_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a> 
                                    <a href="delete-service.php?id=<?php print $service['service_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a> 
                                </td> 
                            </tr> 
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