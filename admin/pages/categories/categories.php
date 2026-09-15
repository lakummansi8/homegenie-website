<?php
$pageTitle = "Categories";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$categories = [];
$query = "SELECT category_id, category_name, category_image, description, category_status, created_at FROM categories ORDER BY category_id DESC";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

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
        <?php if (empty($categories)): ?>
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
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= (int)$category["category_id"] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($category["category_name"]) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($category["description"]) ?></td>
                                <td>
                                    <?php if ($category["category_status"] === "Active"): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($category["created_at"]) ?></td>
                                <td>
                                    <a href="edit-category.php?id=<?= (int)$category["category_id"] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete-category.php?id=<?= (int)$category["category_id"] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
