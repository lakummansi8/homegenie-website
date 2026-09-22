<?php
$pageTitle = "Edit Category";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$categoryId = $_GET['id'];

if($categoryId == "")
{
    header("Location: categories.php?error=invalid_category");
    exit;
}


if(isset($_POST['submit']))
{
    $categoryName = $_POST['category_name'];
    $description = $_POST['description'];
    $categoryStatus = $_POST['category_status'];

    if($categoryName == "")
    {
        header("Location: edit-category.php?id=$categoryId&error=category_name_required");
        exit;
    }

    if($categoryStatus != "Active" && $categoryStatus != "Inactive")
    {
        header("Location: edit-category.php?id=$categoryId&error=invalid_status");
        exit;
    }

    $q = "select * from categories where category_id = $categoryId";
    $res = mysqli_query($conn,$q);

    if(mysqli_num_rows($res) == 0)
    {
        header("Location: categories.php?error=category_not_found");
        exit;
    }

    $category = mysqli_fetch_array($res);

    $oldImage = $category['category_image'];
    $categoryImage = $oldImage;


    if(isset($_FILES['category_image']) && $_FILES['category_image']['name'] != "")
    {
        $imageName = $_FILES['category_image']['name'];
        $imageSize = $_FILES['category_image']['size'];
        $imageTmp = $_FILES['category_image']['tmp_name'];

        if($imageSize > 2 * 1024 * 1024)
        {
            die("Category image must be smaller than 2 MB.");
        }

        $imageType = strtolower(pathinfo($imageName,PATHINFO_EXTENSION));

        if($imageType != "jpg" && $imageType != "jpeg" && $imageType != "png" && $imageType != "webp")
        {
            die("Only JPG, PNG and WEBP images are allowed.");
        }

        $newImageName = "category_" . time() . "." . $imageType;

        $uploadPath = "../../../assets/categories/" . $newImageName;

        move_uploaded_file($imageTmp,$uploadPath);

        $categoryImage = $newImageName;

        if($oldImage != "")
        {
            $oldImagePath = "../../../assets/categories/" . $oldImage;

            if(file_exists($oldImagePath))
            {
                unlink($oldImagePath);
            }
        }
    }


    $q = "update categories set
          category_name = '$categoryName',
          category_image = '$categoryImage',
          description = '$description',
          category_status = '$categoryStatus'
          where category_id = $categoryId";

    $res = mysqli_query($conn,$q);

    if($res)
    {
        header("Location: categories.php?success=category_updated");
        exit;
    }
}


$q = "select * from categories where category_id = $categoryId";
$res = mysqli_query($conn,$q);

if(mysqli_num_rows($res) == 0)
{
    header("Location: categories.php?error=category_not_found");
    exit;
}

$category = mysqli_fetch_array($res);

ob_start();
?>

<div class="admin-page-header"> 
    <div class="header-title"> 
        <h3>Edit Category</h3> 
        <p class="text-muted">Update the details of this service category.</p> 
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
        <form method="POST" action="edit-category.php?id=<?= (int)$category["category_id"] ?>" enctype="multipart/form-data"> 
            <input type="hidden" name="category_id" value="<?= (int)$category["category_id"] ?>"> 
 
            <div class="mb-3"> 
                <label for="categoryName" class="form-label">Category Name</label> 
                <input type="text" class="form-control" id="categoryName" name="category_name" value="<?= htmlspecialchars($category["category_name"] ?? "") ?>" required> 
            </div> 
 
            <?php if (!empty($category["category_image"])): ?> 
                <div class="mb-3"> 
                    <label class="form-label">Current Category Image</label> 
                    <div class="mb-2"> 
                        <img src="<?= $assetPath ?>assets/categories/<?= htmlspecialchars($category["category_image"]) ?>" alt="Category Image" class="img-thumbnail img-preview-lg"> 
                    </div> 
                </div> 
            <?php endif; ?> 
 
            <div class="mb-3"> 
                <label for="categoryImage" class="form-label">Change Category Image</label> 
                <input type="file" class="form-control" id="categoryImage" name="category_image" accept="image/png, image/jpeg, image/webp"> 
                <div class="form-text">JPG, PNG or WEBP. Max size: 2 MB. Leave empty to keep current image.</div> 
            </div> 
 
            <div class="mb-3"> 
                <label for="categoryDescription" class="form-label">Description</label> 
                <textarea class="form-control" id="categoryDescription" name="description" rows="5"><?= htmlspecialchars($category["description"] ?? "") ?></textarea> 
            </div> 
 
            <div class="mb-3"> 
                <label for="categoryStatus" class="form-label">Status</label> 
                <select class="form-select" id="categoryStatus" name="category_status"> 
                    <option value="Active" <?= ($category["category_status"] === "Active") ? "selected" : "" ?>>Active</option> 
                    <option value="Inactive" <?= ($category["category_status"] === "Inactive") ? "selected" : "" ?>>Inactive</option> 
                </select> 
            </div> 
 
            <hr> 
            <div class="admin-form-actions"> 
                <a href="categories.php" class="btn btn-secondary">Cancel</a> 
                <button type="submit" name="submit" class="btn btn-primary">Save Changes</button> 
            </div> 
        </form> 
    </div> 
</div> 
 
<?php 
$pageContent = ob_get_clean(); 
require_once "../../layout/admin-layout.php"; 
?>