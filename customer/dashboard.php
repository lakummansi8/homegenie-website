<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION["user_name"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <div class="dashboard-menu">

        <h2>Welcome, <?php echo htmlspecialchars($userName); ?>!</h2>

        <p>Welcome to your HomeGenie customer dashboard.</p>

        <h3>Customer Menu</h3>

        <p><a href="../index.php">Home</a></p>
        <p><a href="services.php">Browse Services</a></p>
        <p><a href="my-bookings.php">My Bookings</a></p>
        <p><a href="profile.php">My Profile</a></p>
        <p><a href="logout.php">Logout</a></p>

    </div>

</body>
</html>