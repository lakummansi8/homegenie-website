<?php
$pageTitle = "Services";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$services = [];
$query = "
    SELECT s.service_id, s.category_id, s.provider_id, s.service_name, s.description, s.price, s.service_image, s.service_status, s.created_at, c.category_name, sp.full_name AS provider_name
    FROM services s
    LEFT JOIN categories c ON s.category_id = c.category_id
    LEFT JOIN service_providers sp ON s.provider_id = sp.provider_id
    ORDER BY s.service_id DESC
";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

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
        <?php if (empty($services)): ?>
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
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= (int)$service["service_id"] ?></td>
                                <td><strong><?= htmlspecialchars($service["service_name"]) ?></strong></td>
                                <td><?= htmlspecialchars($service["category_name"] ?: "No category") ?></td>
                                <td><?= htmlspecialchars($service["provider_name"] ?: "No provider") ?></td>
                                <td><?= htmlspecialchars($service["description"] ?: "No description") ?></td>
                                <td>₹<?= number_format((float)$service["price"], 2) ?> <small>/ hour</small></td>
                                <td>
                                    <?php if ($service["service_status"] === "Active"): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($service["created_at"]) ?></td>
                                <td>
                                    <a href="edit-service.php?id=<?= (int)$service["service_id"] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete-service.php?id=<?= (int)$service["service_id"] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
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
