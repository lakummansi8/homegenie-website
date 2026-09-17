<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$stmt = $conn->prepare(
    "SELECT
        sp.full_name,
        sp.email,
        sp.phone,
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

$pageTitle = "Provider Dashboard";
$pageCss = "dashboard.css";
$assetPath = "../";
$providerPath = "./";

require_once "layout/provider-layout.php";
?>

<div class="provider-dashboard">

    <div class="dashboard-header">

        <div>
            <h1>Welcome, <?php echo htmlspecialchars($provider["full_name"]); ?></h1>

            <p>
                Manage your services and bookings from here.
            </p>
        </div>

    </div>


    <div class="provider-cards">

        <div class="provider-card">

            <h3>Category</h3>

            <p>
                <?php echo htmlspecialchars($provider["category_name"] ?? "Not assigned"); ?>
            </p>

        </div>


        <div class="provider-card">

            <h3>Experience</h3>

            <p>
                <?php echo htmlspecialchars($provider["experience"]); ?> years
            </p>

        </div>


        <div class="provider-card">

            <h3>Availability</h3>

            <p>
                <?php echo htmlspecialchars($provider["availability"]); ?>
            </p>

        </div>


        <div class="provider-card">

            <h3>Account Status</h3>

            <p>
                <?php echo htmlspecialchars($provider["account_status"]); ?>
            </p>

        </div>

    </div>


    <div class="provider-info">

        <h2>My Information</h2>

        <div class="info-grid">

            <div>
                <strong>Email</strong>
                <span>
                    <?php echo htmlspecialchars($provider["email"]); ?>
                </span>
            </div>

            <div>
                <strong>Phone</strong>
                <span>
                    <?php echo htmlspecialchars($provider["phone"]); ?>
                </span>
            </div>

            <div>
                <strong>Area</strong>
                <span>
                    <?php echo htmlspecialchars($provider["area"]); ?>
                </span>
            </div>

            <div>
                <strong>City</strong>
                <span>
                    <?php echo htmlspecialchars($provider["city"]); ?>
                </span>
            </div>

        </div>

    </div>

</div>