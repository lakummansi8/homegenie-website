<?php
include '../config/db.php';

$bookingResult = $conn->query("SELECT * FROM bookings");
?>
<?php

session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: ../auth/login.html");
    exit;
}

include "../config/db.php";

$adminName = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";

$bookingResult = $conn->query("SELECT * FROM bookings");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - HomeGenie Admin</title>
     <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin-sidebar.css">
</head>


<body>

<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-brand">
            <h2>HomeGenie</h2>
            <span>Admin Panel</span>
        </div>

        <nav>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="users.php">
                Manage Users
            </a>

            <a href="providers.php">
                Manage Providers
            </a>

            <a href="services.php">
                Manage Services
            </a>

            <a href="bookings.php" class="active">
                Manage Bookings
            </a>

            <a href="reviews.php">
                Manage Reviews
            </a>

            <a href="contact.php">
                Manage Contacts
            </a>

        </nav>

        <div class="logout-button">
            <a href="../auth/logout.php">Logout</a>
        </div>

    </aside>


    <!-- MAIN ADMIN AREA -->
    <main class="admin-main">

        <!-- TOP NAVBAR -->
        <header class="admin-navbar">

            <div class="navbar-left">
                <h1>Bookings</h1>
            </div>

            <div class="admin-profile">

                <div class="admin-avatar">
                    <?php echo strtoupper(substr($adminName, 0, 1)); ?>
                </div>

                <div class="admin-info">

                    <strong>
                        <?php echo htmlspecialchars($adminName); ?>
                    </strong>

                    <span>
                        <?php echo htmlspecialchars($adminEmail); ?>
                    </span>

                </div>

            </div>

        </header>


        <!-- PAGE CONTENT -->
        <section class="admin-content">

            <h2>Manage Bookings</h2>

            <div class="table-container">

                <table>

                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User ID</th>
                            <th>Provider ID</th>
                            <th>Service ID</th>
                            <th>Booking Date</th>
                            <th>Booking Time</th>
                            <th>Booking Address</th>
                            <th>Booking Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($booking = $bookingResult->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_id"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["user_id"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["provider_id"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["service_id"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_date"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_time"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_address"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_status"]); ?>
                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>
</html>