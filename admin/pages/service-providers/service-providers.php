<?php
$pageTitle = "Service Providers";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$providers = [];
$query = "
    SELECT sp.provider_id, sp.full_name, sp.email, sp.phone, sp.gender, sp.experience, sp.address, sp.area, sp.city, sp.availability, sp.account_status, sp.profile_image, sp.created_at, c.category_name
    FROM service_providers sp
    LEFT JOIN categories c ON sp.category_id = c.category_id
    ORDER BY sp.provider_id DESC
";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $providers[] = $row;
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Service Providers</h3>
        <p class="text-muted">Manage the service providers available on HomeGenie.</p>
    </div>
    <div class="header-actions">
        <a href="add-service-provider.php" class="btn btn-primary">+ Add Provider</a>
    </div>
</div>

<?php if (isset($_GET["success"])): ?>
    <div class="alert alert-success">
        <?php 
            if ($_GET["success"] === "provider_added") echo "Provider added successfully.";
            elseif ($_GET["success"] === "provider_updated") echo "Provider updated successfully.";
            elseif ($_GET["success"] === "provider_deleted") echo "Provider deleted successfully.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET["error"])): ?>
    <div class="alert alert-danger">
        <?php
            if ($_GET["error"] === "invalid_provider") echo "Invalid service provider.";
            elseif ($_GET["error"] === "provider_not_found") echo "Service provider not found.";
            elseif ($_GET["error"] === "provider_delete_failed") echo "Unable to delete service provider.";
            else echo "Something went wrong.";
        ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>All Service Providers</strong>
    </div>
    <div class="card-body">
        <?php if (empty($providers)): ?>
            <p class="text-muted">No service providers found. Add your first service provider to get started.</p>
            <a href="add-service-provider.php" class="btn btn-primary">+ Add Provider</a>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Provider</th>
                            <th>Contact</th>
                            <th>Category</th>
                            <th>Experience</th>
                            <th>Location</th>
                            <th>Availability</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($providers as $provider): ?>
                            <tr>
                                <td><?= (int)$provider["provider_id"] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($provider["full_name"]) ?></strong><br>
                                    <small><?= htmlspecialchars($provider["gender"] ?: "") ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($provider["email"]) ?><br>
                                    <?= htmlspecialchars($provider["phone"]) ?>
                                </td>
                                <td><?= htmlspecialchars($provider["category_name"] ?: "No category") ?></td>
                                <td><?= ($provider["experience"] !== null) ? (int)$provider["experience"] . " years" : "Not specified" ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($provider["area"] ?: "") ?></strong><br>
                                    <?= htmlspecialchars($provider["city"] ?: "") ?>
                                </td>
                                <td>
                                    <?php if ($provider["availability"] === "Available"): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php elseif ($provider["availability"] === "Busy"): ?>
                                        <span class="badge bg-warning text-dark">Busy</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Offline</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($provider["account_status"] === "Active"): ?>
                                        <span class="badge bg-primary">Active</span>
                                    <?php elseif ($provider["account_status"] === "Blocked"): ?>
                                        <span class="badge bg-danger">Blocked</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($provider["created_at"]) ?></td>
                                <td>
                                    <a href="edit-service-provider.php?id=<?= (int)$provider["provider_id"] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete-service-provider.php?id=<?= (int)$provider["provider_id"] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
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
