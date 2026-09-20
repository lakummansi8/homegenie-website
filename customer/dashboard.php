<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/db.php";

$userId = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT full_name, email, phone, address, city, account_status
     FROM users
     WHERE user_id = ?"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$userName = $user["full_name"];


$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ?"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$totalBookings = $stmt->get_result()->fetch_assoc()["total"];


$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ? AND booking_status = 'Pending'"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$pendingBookings = $stmt->get_result()->fetch_assoc()["total"];


$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ? AND booking_status = 'Completed'"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$completedBookings = $stmt->get_result()->fetch_assoc()["total"];


$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ? AND booking_status = 'Cancelled'"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$cancelledBookings = $stmt->get_result()->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>

<body>

<div class="customer-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">
            <h1>HomeGenie</h1>
            <p>Customer Panel</p>
        </div>

        <nav class="sidebar-nav">

            <a href="dashboard.php" class="active">
                Dashboard
            </a>

            <a href="services.php">
                Services
            </a>

            <a href="my-bookings.php">
                My Bookings
            </a>

            <a href="profile.php">
                My Profile
            </a>

        </nav>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </aside>


    <main class="main-area">

        <header class="top-header">

            <h2>Customer Dashboard</h2>

            <div class="header-user">
                <strong><?php echo htmlspecialchars($userName); ?></strong>
                <span>Customer</span>
            </div>

        </header>


        <div class="dashboard">

            <div class="welcome">

                <h1>
                    Welcome, <?php echo htmlspecialchars($userName); ?>
                </h1>

                <p>
                    Manage your services, bookings and profile from here.
                </p>

            </div>


            <div class="stats">

                <div class="stat-card">
                    <p>Total Bookings</p>
                    <strong><?php echo $totalBookings; ?></strong>
                </div>

                <div class="stat-card">
                    <p>Pending Bookings</p>
                    <strong><?php echo $pendingBookings; ?></strong>
                </div>

                <div class="stat-card">
                    <p>Completed Bookings</p>
                    <strong><?php echo $completedBookings; ?></strong>
                </div>

                <div class="stat-card">
                    <p>Cancelled Bookings</p>
                    <strong><?php echo $cancelledBookings; ?></strong>
                </div>

            </div>


            <div class="information">

                <h2>My Information</h2>

                <div class="information-grid">

                    <div>
                        <span>Full Name</span>
                        <p><?php echo htmlspecialchars($user["full_name"]); ?></p>
                    </div>

                    <div>
                        <span>Phone</span>
                        <p><?php echo htmlspecialchars($user["phone"]); ?></p>
                    </div>

                    <div>
                        <span>Account Status</span>
                        <p class="active-status">
                            <?php echo htmlspecialchars($user["account_status"]); ?>
                        </p>
                    </div>

                    <div>
                        <span>Email</span>
                        <p><?php echo htmlspecialchars($user["email"]); ?></p>
                    </div>

                    <div>
                        <span>City</span>
                        <p><?php echo htmlspecialchars($user["city"]); ?></p>
                    </div>

                    <div>
                        <span>Address</span>
                        <p><?php echo htmlspecialchars($user["address"]); ?></p>
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>