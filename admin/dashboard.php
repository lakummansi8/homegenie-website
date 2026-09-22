<?php

$pageTitle = "Dashboard";
$assetPath = "../";
$adminPath = "";

require_once "../config/db.php";


/* Total Customers */

$q = "select count(*) as count from users";
$res = mysqli_query($conn,$q);
$row = mysqli_fetch_array($res);
$totalUsers = $row['count'];


/* Total Providers */

$q = "select count(*) as cnt from service_providers";
$res = mysqli_query($conn,$q);
$row = mysqli_fetch_array($res);
$totalProviders = $row['cnt'];


/* Total Services */

$q = "select count(*) as count from services";
$res = mysqli_query($conn,$q);
$row = mysqli_fetch_array($res);
$totalServices = $row['count'];


/* Total Bookings */

$q = "select count(*) as count from bookings";
$res = mysqli_query($conn,$q);
$row = mysqli_fetch_array($res);
$totalBookings = $row['count'];


/* Recent Bookings */

$q = "select b.booking_id, b.booking_date, b.booking_status,
u.full_name as user_name,
sp.full_name as provider_name
from bookings b
left join users u on b.user_id = u.user_id
left join service_providers sp on b.provider_id = sp.provider_id
order by b.booking_id desc
limit 5";

$recentBookings = mysqli_query($conn,$q);

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

        <?php if(mysqli_num_rows($recentBookings) == 0) { ?>

            <p class="text-muted">No bookings yet.</p>

        <?php } else { ?>

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

                        <?php while($booking = mysqli_fetch_array($recentBookings)) { ?>

                            <tr>
                                <td><?= $booking['booking_id'] ?></td>

                                <td>
                                    <?php
                                    if($booking['user_name'] != "")
                                    {
                                        print $booking['user_name'];
                                    }
                                    else
                                    {
                                        print "Unknown";
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if($booking['provider_name'] != "")
                                    {
                                        print $booking['provider_name'];
                                    }
                                    else
                                    {
                                        print "Unavailable";
                                    }
                                    ?>
                                </td>

                                <td><?= $booking['booking_date'] ?></td>

                                <td>
                                    <?php

                                    $status = strtolower($booking['booking_status']);
                                    $badgeClass = "bg-secondary";

                                    if($status == "pending")
                                    {
                                        $badgeClass = "bg-warning text-dark";
                                    }
                                    elseif($status == "confirmed")
                                    {
                                        $badgeClass = "bg-primary";
                                    }
                                    elseif($status == "completed")
                                    {
                                        $badgeClass = "bg-success";
                                    }
                                    elseif($status == "cancelled" || $status == "canceled")
                                    {
                                        $badgeClass = "bg-danger";
                                    }

                                    ?>

                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>
            </div>

            <a href="<?= $adminPath ?>pages/bookings/bookings.php" class="btn btn-outline-dark btn-sm">View All Bookings</a>

        <?php } ?>

    </div>
</div>

<?php

$pageContent = ob_get_clean();
require_once "layout/admin-layout.php";

?>