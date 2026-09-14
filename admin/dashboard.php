<?php

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD (Student Edition)
|--------------------------------------------------------------------------
*/

$pageTitle = "Dashboard";
$assetPath = "../";
$adminPath = "";

require_once "../config/db.php";

/* Total Statistics */
$totalUsers = 0;
$totalProviders = 0;
$totalServices = 0;
$totalBookings = 0;

$userResult = $conn->query("SELECT COUNT(*) AS count FROM users");
if ($userResult) $totalUsers = $userResult->fetch_assoc()["count"];

$providerResult = $conn->query("SELECT COUNT(*) AS count FROM service_providers");
if ($providerResult) $totalProviders = $providerResult->fetch_assoc()["count"];

$serviceResult = $conn->query("SELECT COUNT(*) AS count FROM services");
if ($serviceResult) $totalServices = $serviceResult->fetch_assoc()["count"];

$bookingResult = $conn->query("SELECT COUNT(*) AS count FROM bookings");
if ($bookingResult) $totalBookings = $bookingResult->fetch_assoc()["count"];

/* Recent Bookings */
$recentBookings = [];
$recentBookingResult = $conn->query(
    "SELECT b.booking_id, b.booking_date, b.booking_status, u.full_name AS user_name, sp.full_name AS provider_name
     FROM bookings b
     LEFT JOIN users u ON b.user_id = u.user_id
     LEFT JOIN service_providers sp ON b.provider_id = sp.provider_id
     ORDER BY b.booking_id DESC LIMIT 5"
);
if ($recentBookingResult) {
    while ($row = $recentBookingResult->fetch_assoc()) {
        $recentBookings[] = $row;
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <p class="lead">Welcome to the HomeGenie Admin Dashboard. Here you can monitor platform activity.</p>
    </div>
    <div class="header-actions">
        <a href="<?= $adminPath ?>pages/services/add-service.php" class="btn btn-primary">Add Service</a>
        <a href="<?= $adminPath ?>pages/service-providers/add-service-provider.php" class="btn btn-secondary">Add Provider</a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="admin-stats-grid">
    <div class="stat-card-wrapper">
        <div class="card text-center bg-primary text-white mb-3">
            <div class="card-body">
                <h3 class="card-title"><?= $totalUsers ?></h3>
                <p class="card-text">Total Customers</p>
                <a href="<?= $adminPath ?>pages/users/users.php" class="text-white text-decoration-none">View Users &rarr;</a>
            </div>
        </div>
    </div>
    <div class="stat-card-wrapper">
        <div class="card text-center bg-success text-white mb-3">
            <div class="card-body">
                <h3 class="card-title"><?= $totalProviders ?></h3>
                <p class="card-text">Service Providers</p>
                <a href="<?= $adminPath ?>pages/service-providers/service-providers.php" class="text-white text-decoration-none">Manage Providers &rarr;</a>
            </div>
        </div>
    </div>
    <div class="stat-card-wrapper">
        <div class="card text-center bg-info text-white mb-3">
            <div class="card-body">
                <h3 class="card-title"><?= $totalServices ?></h3>
                <p class="card-text">Total Services</p>
                <a href="<?= $adminPath ?>pages/services/services.php" class="text-white text-decoration-none">Manage Services &rarr;</a>
            </div>
        </div>
    </div>
    <div class="stat-card-wrapper">
        <div class="card text-center bg-warning text-dark mb-3">
            <div class="card-body">
                <h3 class="card-title"><?= $totalBookings ?></h3>
                <p class="card-text">Total Bookings</p>
                <a href="<?= $adminPath ?>pages/bookings/bookings.php" class="text-dark text-decoration-none">View Bookings &rarr;</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <strong>Recent Bookings</strong>
    </div>
    <div class="card-body">
        <?php if (empty($recentBookings)): ?>
            <p class="text-muted">No bookings yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Provider</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td><?= (int)$booking["booking_id"] ?></td>
                                <td><?= htmlspecialchars($booking["user_name"] ?: "Unknown") ?></td>
                                <td><?= htmlspecialchars($booking["provider_name"] ?: "Unavailable") ?></td>
                                <td><?= htmlspecialchars($booking["booking_date"]) ?></td>
                                <td>
                                    <?php
                                        $status = strtolower($booking["booking_status"]);
                                        $badgeClass = "bg-secondary";
                                        if ($status == "pending") $badgeClass = "bg-warning text-dark";
                                        if ($status == "confirmed") $badgeClass = "bg-primary";
                                        if ($status == "completed") $badgeClass = "bg-success";
                                        if ($status == "cancelled" || $status == "canceled") $badgeClass = "bg-danger";
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="<?= $adminPath ?>pages/bookings/bookings.php" class="btn btn-outline-dark btn-sm">View All Bookings</a>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "layout/admin-layout.php";
?>