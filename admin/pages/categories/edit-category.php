<?php
$pageTitle = "Edit Category";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$categoryId = (int)($_GET["id"] ?? $_POST["category_id"] ?? 0);
if ($categoryId <= 0) {
    header("Location: categories.php?error=invalid_category");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $categoryName = trim($_POST["category_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $categoryStatus = trim($_POST["category_status"] ?? "Active");

    if ($categoryName === "") {
        header("Location: edit-category.php?id=" . $categoryId . "&error=category_name_required");
        exit;
    }

    if ($categoryStatus !== "Active" && $categoryStatus !== "Inactive") {
        header("Location: edit-category.php?id=" . $categoryId . "&error=invalid_status");
        exit;
    }

    $stmt = $conn->prepare("SELECT category_image FROM categories WHERE category_id = ? LIMIT 1");
    if (!$stmt) die("Failed to prepare query: " . $conn->error);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows !== 1) {
        $stmt->close();
        header("Location: categories.php?error=category_not_found");
        exit;
    }
    
    $category = $result->fetch_assoc();
    $stmt->close();

    $oldCategoryImage = $category["category_image"];
    $categoryImage = $oldCategoryImage;
    $newImageUploaded = false;
    $newImagePath = null;

    if (isset($_FILES["category_image"]) && $_FILES["category_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES["category_image"]["error"] !== UPLOAD_ERR_OK) die("Failed to upload category image.");
        if ($_FILES["category_image"]["size"] > 2 * 1024 * 1024) die("Category image must be smaller than 2 MB.");

        $allowedTypes = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
        $imageInfo = getimagesize($_FILES["category_image"]["tmp_name"]);
        if ($imageInfo === false) die("Uploaded file is not a valid image.");

        $mimeType = $imageInfo["mime"];
        if (!isset($allowedTypes[$mimeType])) die("Only JPG, PNG and WEBP images are allowed.");

        $extension = $allowedTypes[$mimeType];
        $newImageName = "category_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
        $uploadDirectory = "../../../assets/categories/";

        if (!is_dir($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0755, true)) die("Failed to create image upload directory.");
        }

        $newImagePath = $uploadDirectory . $newImageName;
        if (!move_uploaded_file($_FILES["category_image"]["tmp_name"], $newImagePath)) die("Failed to save category image.");

        $categoryImage = $newImageName;
        $newImageUploaded = true;
    }

    $stmt = $conn->prepare("UPDATE categories SET category_name = ?, category_image = ?, description = ?, category_status = ? WHERE category_id = ?");
    if (!$stmt) {
        if ($newImageUploaded && $newImagePath !== null && file_exists($newImagePath)) unlink($newImagePath);
        die("Failed to prepare update query: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $categoryName, $categoryImage, $description, $categoryStatus, $categoryId);

    if (!$stmt->execute()) {
        if ($newImageUploaded && $newImagePath !== null && file_exists($newImagePath)) unlink($newImagePath);
        $error = $stmt->error;
        $stmt->close();
        die("Failed to update category: " . $error);
    }
    $stmt->close();

    if ($newImageUploaded && !empty($oldCategoryImage)) {
        $oldImagePath = "../../../assets/categories/" . $oldCategoryImage;
        if (file_exists($oldImagePath)) unlink($oldImagePath);
    }

    header("Location: categories.php?success=category_updated");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id = ? LIMIT 1");
if (!$stmt) die("Failed to prepare query: " . $conn->error);
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    header("Location: categories.php?error=category_not_found");
    exit;
}

$category = $result->fetch_assoc();
$stmt->close();

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
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
