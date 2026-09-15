<?php
$pageTitle = "Add Category";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $categoryName = trim($_POST["category_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $categoryStatus = trim($_POST["category_status"] ?? "Active");

    if ($categoryName === "") {
        header("Location: add-category.php?error=category_name_required");
        exit;
    }

    if ($categoryStatus !== "Active" && $categoryStatus !== "Inactive") {
        header("Location: add-category.php?error=invalid_status");
        exit;
    }

    $categoryImage = null;
    if (isset($_FILES["category_image"]) && $_FILES["category_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES["category_image"]["error"] !== UPLOAD_ERR_OK) {
            die("Failed to upload category image.");
        }

        if ($_FILES["category_image"]["size"] > 2 * 1024 * 1024) {
            die("Category image must be smaller than 2 MB.");
        }

        $allowedTypes = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
        $imageInfo = getimagesize($_FILES["category_image"]["tmp_name"]);
        if ($imageInfo === false) {
            die("Uploaded file is not a valid image.");
        }

        $mimeType = $imageInfo["mime"];
        if (!isset($allowedTypes[$mimeType])) {
            die("Only JPG, PNG and WEBP images are allowed.");
        }

        $extension = $allowedTypes[$mimeType];
        $categoryImage = "category_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
        $uploadDirectory = "../../../assets/categories/";

        if (!is_dir($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0755, true)) {
                die("Failed to create image upload directory.");
            }
        }

        $uploadPath = $uploadDirectory . $categoryImage;
        if (!move_uploaded_file($_FILES["category_image"]["tmp_name"], $uploadPath)) {
            die("Failed to save category image.");
        }
    }

    $stmt = $conn->prepare("INSERT INTO categories (category_name, category_image, description, category_status) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Failed to prepare category query: " . $conn->error);
    }

    $stmt->bind_param("ssss", $categoryName, $categoryImage, $description, $categoryStatus);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: categories.php?success=category_added");
        exit;
    }

    $error = $stmt->error;
    $stmt->close();
    die("Failed to add category: " . $error);
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
