<?php
$pageTitle = "Customers";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$users = [];
$userResult = $conn->query("SELECT user_id, full_name, email, phone, address, city, created_at, account_status FROM users ORDER BY user_id DESC");
if ($userResult) {
    while ($userRow = $userResult->fetch_assoc()) {
        $users[] = $userRow;
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Customers</h3>
        <p class="text-muted">View and manage HomeGenie customer accounts.</p>
    </div>
    <div class="header-actions">
        <a href="add-user.php" class="btn btn-primary">+ Add Customer</a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white admin-card-header">
        <strong>All Customers</strong>
        <span class="badge bg-light text-dark"><?= count($users) ?> Customers</span>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <p class="text-muted">There are currently no customer accounts in the system.</p>
            <a href="add-user.php" class="btn btn-primary">Add First Customer</a>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php
                                $userId = (int)($user["user_id"] ?? 0);
                                $fullName = trim((string)($user["full_name"] ?? "Unknown"));
                                $email = trim((string)($user["email"] ?? ""));
                                $phone = trim((string)($user["phone"] ?? ""));
                                $address = trim((string)($user["address"] ?? ""));
                                $city = trim((string)($user["city"] ?? ""));
                                $accountStatus = trim((string)($user["account_status"] ?? "Unknown"));
                                $registered = !empty($user["created_at"]) ? date("d M Y", strtotime($user["created_at"])) : "—";
                            ?>
                            <tr>
                                <td>#<?= $userId ?></td>
                                <td><strong><?= htmlspecialchars($fullName) ?></strong></td>
                                <td>
                                    <?= htmlspecialchars($email) ?><br>
                                    <?= htmlspecialchars($phone) ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($city) ?></strong><br>
                                    <?= htmlspecialchars($address) ?>
                                </td>
                                <td><?= htmlspecialchars($registered) ?></td>
                                <td>
                                    <?php if ($accountStatus === "Active"): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($accountStatus === "Blocked"): ?>
                                        <span class="badge bg-danger">Blocked</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($accountStatus) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit-user.php?id=<?= $userId ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete-user.php?id=<?= $userId ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
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
