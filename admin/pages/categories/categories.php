<?php 
$pageTitle = "Categories"; 
$assetPath = "../../../"; 
$adminPath = "../../"; 
 
require_once "../../../config/db.php"; 
 
$q = "select * from categories"; 
$res = mysqli_query($conn,$q); 

$totalCategories = mysqli_num_rows($res);

ob_start(); 
?> 
 
<div class="admin-page-header">  

    <div class="header-title">  
        <h3>Categories</h3>  
        <p class="text-muted">
            Manage the service categories available on HomeGenie.
        </p>  
    </div>  

    <div class="header-actions">  
        <a href="add-category.php" class="btn btn-primary">
            + Add Category
        </a>  
    </div>  

</div>  


<?php if (isset($_GET["success"])): ?>  

    <div class="alert alert-success">  

        <?php   
            if ($_GET["success"] === "category_added")
            {
                echo "Category added successfully.";
            }
            elseif ($_GET["success"] === "category_updated")
            {
                echo "Category updated successfully.";
            }
            elseif ($_GET["success"] === "category_deleted")
            {
                echo "Category deleted successfully.";
            }
        ?>  

    </div>  

<?php endif; ?>  


<?php if (isset($_GET["error"])): ?>  

    <div class="alert alert-danger">  

        <?php
            if ($_GET["error"] === "invalid_category")
            {
                echo "Invalid category.";
            }
            elseif ($_GET["error"] === "category_not_found")
            {
                echo "Category not found.";
            }
            else
            {
                echo "Something went wrong.";
            }
        ?>  

    </div>  

<?php endif; ?>  


<div class="card categories-card">  

    <div class="card-header bg-dark text-white categories-card-header">  

        <strong>All Categories</strong>

        <span class="category-count">
            <?php print $totalCategories; ?> Categories
        </span>

    </div>  


    <div class="card-body">  

        <?php if ($totalCategories == 0): ?>  

            <p class="text-muted">
                No categories found. Create your first category to get started.
            </p>  

            <a href="add-category.php" class="btn btn-primary">
                + Add Category
            </a>  

        <?php else: ?>  

            <div class="table-responsive">  

                <table class="table table-bordered table-striped categories-table">  

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

                    <?php $srno = 1;?>
                    
                        <?php while($category = mysqli_fetch_array($res)) { ?>  

                            <tr>  

                                <td><?php print $srno; ?></td>


                                <td>

                                    <div class="category-info">

                                        <?php if ($category["category_image"] != "") { ?>

                                            <img
                                                src="../../../assets/categories/<?php print $category["category_image"]; ?>"
                                                alt="<?php print $category["category_name"]; ?>"
                                                style="
                                                    width: 55px !important;
                                                    height: 45px !important;
                                                    min-width: 55px !important;
                                                    max-width: 55px !important;
                                                    min-height: 45px !important;
                                                    max-height: 45px !important;
                                                    object-fit: cover !important;
                                                    display: block !important;
                                                    border-radius: 5px !important;
                                                    border: 1px solid #dee2e6 !important;
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
                                                    border: 1px solid #dee2e6;
                                                    border-radius: 5px;
                                                    color: #6c757d;
                                                    background: #f8f9fa;
                                                "
                                            >
                                                -
                                            </div>

                                        <?php } ?>


                                        <strong>
                                            <?php print $category["category_name"]; ?>
                                        </strong>

                                    </div>

                                </td>  


                                <td>

                                    <?php

                                        if ($category["description"] != "")
                                        {
                                            print $category["description"];
                                        }
                                        else
                                        {
                                            print "-";
                                        }

                                    ?>

                                </td>  


                                <td>

                                    <?php if ($category["category_status"] == "Active") { ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    <?php } ?>

                                </td>  


                                <td>

                                    <?php 
                                        print date(
                                            "d M Y",
                                            strtotime($category["created_at"])
                                        ); 
                                    ?>

                                </td>  


                                <td>

                                    <a
                                        href="edit-category.php?id=<?php print $category["category_id"]; ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete-category.php?id=<?php print $category["category_id"]; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure?');"
                                    >
                                        Delete
                                    </a>

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