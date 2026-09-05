<?php

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
| This page:
| 1. Checks admin authentication through the shared admin layout.
| 2. Gets dashboard statistics from the database.
| 3. Sends the dashboard content to the shared Admin Layout.
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| PAGE INFORMATION
|--------------------------------------------------------------------------
*/

$pageTitle = "Dashboard";
$pageCss = "dashboard.css";
$assetPath = "../";
$adminPath = "";

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

require_once "../config/db.php";


/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/


// Total Users
$userResult = $conn->query(
    "SELECT COUNT(*) AS total_users FROM users"
);

$totalUsers = 0;

if ($userResult) {
    $userData = $userResult->fetch_assoc();
    $totalUsers = (int) $userData["total_users"];
}


// Total Providers
$providerResult = $conn->query(
    "SELECT COUNT(*) AS total_providers FROM service_providers"
);

$totalProviders = 0;

if ($providerResult) {
    $providerData = $providerResult->fetch_assoc();
    $totalProviders = (int) $providerData["total_providers"];
}


// Total Services
$serviceResult = $conn->query(
    "SELECT COUNT(*) AS total_services FROM services"
);

$totalServices = 0;

if ($serviceResult) {
    $serviceData = $serviceResult->fetch_assoc();
    $totalServices = (int) $serviceData["total_services"];
}


// Total Bookings
$bookingResult = $conn->query(
    "SELECT COUNT(*) AS total_bookings FROM bookings"
);

$totalBookings = 0;

if ($bookingResult) {
    $bookingData = $bookingResult->fetch_assoc();
    $totalBookings = (int) $bookingData["total_bookings"];
}


// Total Reviews
$reviewResult = $conn->query(
    "SELECT COUNT(*) AS total_reviews FROM reviews"
);

$totalReviews = 0;

if ($reviewResult) {
    $reviewData = $reviewResult->fetch_assoc();
    $totalReviews = (int) $reviewData["total_reviews"];
}


// Total Contact Messages
$contactResult = $conn->query(
    "SELECT COUNT(*) AS total_contacts FROM contact"
);

$totalContacts = 0;

if ($contactResult) {
    $contactData = $contactResult->fetch_assoc();
    $totalContacts = (int) $contactData["total_contacts"];
}


/*
|--------------------------------------------------------------------------
| DASHBOARD CONTENT
|--------------------------------------------------------------------------
|
| Everything inside this variable will be displayed inside:
| admin/layout/admin-layout.php
|
|--------------------------------------------------------------------------
*/

ob_start();

?>

<!-- =========================================================
     DASHBOARD HEADER
========================================================= -->

<div class="dashboard-header">

    <div>

        <h2>
            Overview
        </h2>

        <p class="dashboard-subtitle">
            Welcome to the HomeGenie administration panel.
        </p>

    </div>

</div>


<!-- =========================================================
     STATISTICS
========================================================= -->

<div class="stats-container">


    <!-- USERS -->

    <div class="stat-card">

        <div class="stat-card-content">

            <span class="stat-label">
                Total Users
            </span>

            <strong class="stat-value">
                <?= $totalUsers ?>
            </strong>

        </div>

    </div>


    <!-- PROVIDERS -->

    <div class="stat-card">

        <div class="stat-card-content">

            <span class="stat-label">
                Total Providers
            </span>

            <strong class="stat-value">
                <?= $totalProviders ?>
            </strong>

        </div>

    </div>


    <!-- SERVICES -->

    <div class="stat-card">

        <div class="stat-card-content">

            <span class="stat-label">
                Total Services
            </span>

            <strong class="stat-value">
                <?= $totalServices ?>
            </strong>

        </div>

    </div>


    <!-- BOOKINGS -->

    <div class="stat-card">

        <div class="stat-card-content">

            <span class="stat-label">
                Total Bookings
            </span>

            <strong class="stat-value">
                <?= $totalBookings ?>
            </strong>

        </div>

    </div>


    <!-- REVIEWS -->

    <div class="stat-card">

        <div class="stat-card-content">

            <span class="stat-label">
                Total Reviews
            </span>

            <strong class="stat-value">
                <?= $totalReviews ?>
            </strong>

        </div>

    </div>


    <!-- CONTACTS -->

    <div class="stat-card">

        <div class="stat-card-content">

            <span class="stat-label">
                Total Contacts
            </span>

            <strong class="stat-value">
                <?= $totalContacts ?>
            </strong>

        </div>

    </div>


</div>


<?php

$pageContent = ob_get_clean();


/*
|--------------------------------------------------------------------------
| LOAD SHARED ADMIN LAYOUT
|--------------------------------------------------------------------------
*/

require_once "layout/admin-layout.php";

?>