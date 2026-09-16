<?php

$pageTitle = "Add Category";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $categoryName = trim($_POST["category_name"]);
    $description = trim($_POST["description"]);
    $categoryStatus = $_POST["category_status"];

    // Check category name
    if ($categoryName == "") {
        die("Category name is required.");
    }

    // Check status
    if ($categoryStatus != "Active" && $categoryStatus != "Inactive") {
        die("Invalid category status.");
    }

    // Handle category image
    $categoryImage = null;

    if (isset($_FILES["category_image"]) && $_FILES["category_image"]["error"] == 0) {

        $imageName = $_FILES["category_image"]["name"];
        $imageSize = $_FILES["category_image"]["size"];
        $imageTmp = $_FILES["category_image"]["tmp_name"];

        // Check image size
        if ($imageSize > 2 * 1024 * 1024) {
            die("Category image must be smaller than 2 MB.");
        }

        // Get file extension
        $extension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        // Check file type
        if ($extension != "jpg" && $extension != "jpeg" && $extension != "png" && $extension != "webp") {
            die("Only JPG, PNG and WEBP images are allowed.");
        }

        // Create a unique image name
        $categoryImage = "category_" . time() . "." . $extension;

        $uploadDirectory = "../../../assets/categories/";

        // Create folder if it does not exist
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $uploadPath = $uploadDirectory . $categoryImage;

        // Save image
        if (!move_uploaded_file($imageTmp, $uploadPath)) {
            die("Failed to upload category image.");
        }
    }

    // Insert category into database
    $stmt = $conn->prepare(
        "INSERT INTO categories 
        (category_name, category_image, description, category_status)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssss",
        $categoryName,
        $categoryImage,
        $description,
        $categoryStatus
    );

    if ($stmt->execute()) {
        $stmt->close();

        header("Location: categories.php?success=category_added");
        exit;
    }

    die("Failed to add category.");
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
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
