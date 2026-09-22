<?php
$pageTitle = "Categories";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$q = "select * from categories";
$res = mysqli_query($conn,$q);

ob_start();
?>

<div class="admin-page-header"> 
    <div class="header-title"> 
        <h3>Categories</h3> 
        <p class="text-muted">Manage the service categories available on HomeGenie.</p> 
    </div> 
    <div class="header-actions"> 
        <a href="add-category.php" class="btn btn-primary">+ Add Category</a> 
    </div> 
</div> 
 
<?php if (isset($_GET["success"])): ?> 
    <div class="alert alert-success"> 
        <?php  
            if ($_GET["success"] === "category_added") echo "Category added successfully."; 
            elseif ($_GET["success"] === "category_updated") echo "Category updated successfully."; 
            elseif ($_GET["success"] === "category_deleted") echo "Category deleted successfully."; 
        ?> 
    </div> 
<?php endif; ?> 
 
<?php if (isset($_GET["error"])): ?> 
    <div class="alert alert-danger"> 
        <?php 
            if ($_GET["error"] === "invalid_category") echo "Invalid category."; 
            elseif ($_GET["error"] === "category_not_found") echo "Category not found."; 
            else echo "Something went wrong."; 
        ?> 
    </div> 
<?php endif; ?> 
 
<div class="card"> 
    <div class="card-header bg-dark text-white"> 
        <strong>All Categories</strong> 
    </div> 
    <div class="card-body"> 
        <?php if (mysqli_num_rows($res) == 0): ?> 
            <p class="text-muted">No categories found. Create your first category to get started.</p> 
            <a href="add-category.php" class="btn btn-primary">+ Add Category</a> 
        <?php else: ?> 
            <div class="table-responsive"> 
                <table class="table table-bordered table-striped"> 
                    <thead class="table-light"> 
                        <tr> 
                            <th>ID</th> 
                            <th>Category</th> 
                            <th>Description</th> 
                            <th>Status</th> 
                            <th>Created</th> 
                            <th>Actions</th> 
                        </tr> 
                    </thead> 
                    <tbody> 

                        <?php while($category = mysqli_fetch_array($res)) { ?>

                            <tr> 
                                <td><?php print $category["category_id"]; ?></td> 

                                <td> 
                                    <strong><?php print $category["category_name"]; ?></strong> 
                                </td> 

                                <td><?php print $category["description"]; ?></td> 

                                <td> 
                                    <?php if ($category["category_status"] == "Active") { ?> 
                                        <span class="badge bg-success">Active</span> 
                                    <?php } else { ?> 
                                        <span class="badge bg-secondary">Inactive</span> 
                                    <?php } ?> 
                                </td> 

                                <td><?php print $category["created_at"]; ?></td> 

                                <td> 
                                    <a href="edit-category.php?id=<?php print $category["category_id"]; ?>" class="btn btn-sm btn-outline-primary">Edit</a> 

                                    <a href="delete-category.php?id=<?php print $category["category_id"]; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a> 
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