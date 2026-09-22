<?php

$pageTitle = "Add Category";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$categoryName = "";
$description = "";
$categoryStatus = "Active";
$error = "";

if(isset($_POST['submit']))
{
    $categoryName = $_POST['category_name'];
    $description = $_POST['description'];
    $categoryStatus = $_POST['category_status'];

    if($categoryName == "")
    {
        $error = "Category name is required.";
    }
    elseif($categoryStatus != "Active" && $categoryStatus != "Inactive")
    {
        $error = "Invalid category status.";
    }

    if($error == "")
    {
        $categoryImage = "";

        if($_FILES['category_image']['name'] != "")
        {
            $imageName = $_FILES['category_image']['name'];

            move_uploaded_file(
                $_FILES['category_image']['tmp_name'],
                "../../../assets/categories/" . $imageName
            );

            $categoryImage = $imageName;
        }

        $q = "insert into categories
        (category_name, category_image, description, category_status)
        values
        ('$categoryName','$categoryImage','$description','$categoryStatus')";

        $res = mysqli_query($conn,$q);

        if($res)
        {
            header("Location: categories.php?success=category_added");
            exit;
        }
        else
        {
            $error = "Unable to add category.";
        }
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Add Category</h3>
        <p class="text-muted">Create a new service category for HomeGenie.</p>
    </div>
    <div class="header-actions">
        <a href="categories.php" class="btn btn-secondary">&larr; Back to Categories</a>
    </div>
</div>

<?php if (isset($_GET["error"])): ?>
    <div class="alert alert-danger">
        <?php
            if ($_GET["error"] === "category_name_required") echo "Category name is required.";
            elseif ($_GET["error"] === "invalid_status") echo "Invalid category status.";
            else echo "Something went wrong.";
        ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Category Information</strong>
    </div>
    <div class="card-body">
        <form method="POST" action="add-category.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="categoryName" name="category_name" required placeholder="Enter category name">
            </div>

            <div class="mb-3">
                <label for="categoryImage" class="form-label">Category Image</label>
                <input type="file" class="form-control" id="categoryImage" name="category_image" accept="image/png, image/jpeg, image/webp">
                <div class="form-text">JPG, PNG or WEBP. Maximum size: 2 MB.</div>
            </div>

            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" name="description" rows="5" placeholder="Enter category description"></textarea>
            </div>

            <div class="mb-3">
                <label for="categoryStatus" class="form-label">Status</label>
                <select class="form-select" id="categoryStatus" name="category_status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <hr>
            <div class="admin-form-actions">
                <a href="categories.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>