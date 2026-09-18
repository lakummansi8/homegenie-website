<?php
require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$stmt = $conn->prepare(
    "SELECT
        sp.full_name,
        sp.email,
        sp.experience,
        sp.area,
        sp.city,
        sp.availability,
        sp.account_status,
        c.category_name
     FROM service_providers sp
     LEFT JOIN categories c
        ON sp.category_id = c.category_id
     WHERE sp.provider_id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();
$provider = $result->fetch_assoc();

$stmt->close();


$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total_services
     FROM services
     WHERE provider_id = ?"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();
$serviceData = $result->fetch_assoc();

$totalServices = $serviceData["total_services"];

$stmt->close();


$stmt = $conn->prepare(
    "SELECT
        COUNT(*) AS total_bookings,
        SUM(CASE WHEN booking_status = 'Pending' THEN 1 ELSE 0 END) AS pending_bookings,
        SUM(CASE WHEN booking_status = 'Confirmed' THEN 1 ELSE 0 END) AS confirmed_bookings
     FROM bookings
     WHERE provider_id = ?"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();
$bookingData = $result->fetch_assoc();

$totalBookings = $bookingData["total_bookings"];
$pendingBookings = $bookingData["pending_bookings"] ?? 0;
$confirmedBookings = $bookingData["confirmed_bookings"] ?? 0;

$stmt->close();


$pageTitle = "Provider Dashboard";
$pageCss = "dashboard.css";

require_once "layout/provider-layout.php";
?>

<div class="provider-dashboard">

    <div class="dashboard-welcome">
        <h2>
            Welcome, <?php echo htmlspecialchars($provider["full_name"]); ?>
        </h2>

        <p>
            Manage your services, bookings and profile from here.
        </p>
    </div>


    <div class="dashboard-cards">

        <div class="dashboard-card">
            <div class="card-content">
                <span class="card-label">My Services</span>
                <h3><?php echo $totalServices; ?></h3>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-content">
                <span class="card-label">Total Bookings</span>
                <h3><?php echo $totalBookings; ?></h3>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-content">
                <span class="card-label">Pending Bookings</span>
                <h3><?php echo $pendingBookings; ?></h3>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-content">
                <span class="card-label">Confirmed Bookings</span>
                <h3><?php echo $confirmedBookings; ?></h3>
            </div>
        </div>

    </div>


    <div class="dashboard-section">

        <div class="section-header">
            <h3>My Information</h3>
        </div>

        <div class="provider-info-grid">

            <div class="info-item">
                <span>Category</span>
                <strong>
                    <?php echo htmlspecialchars($provider["category_name"] ?? "Not Assigned"); ?>
                </strong>
            </div>

            <div class="info-item">
                <span>Experience</span>
                <strong>
                    <?php echo htmlspecialchars($provider["experience"]); ?>
                </strong>
            </div>

            <div class="info-item">
                <span>Availability</span>
                <strong>
                    <?php echo htmlspecialchars($provider["availability"]); ?>
                </strong>
            </div>

            <div class="info-item">
                <span>Account Status</span>
                <strong class="account-status">
                    <?php echo htmlspecialchars($provider["account_status"]); ?>
                </strong>
            </div>

            <div class="info-item">
                <span>Email</span>
                <strong>
                    <?php echo htmlspecialchars($provider["email"]); ?>
                </strong>
            </div>

            <div class="info-item">
                <span>Location</span>
                <strong>
                    <?php
                    echo htmlspecialchars(
                        $provider["area"] . ", " . $provider["city"]
                    );
                    ?>
                </strong>
            </div>

        </div>

    </div>

</div>

</section>
</main>
</div>

</body>
</html>