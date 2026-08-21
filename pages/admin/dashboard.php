<?php
include '../../php/db.php';
$userResult = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$userData = $userResult->fetch_assoc();
$totalUsers = $userData['total_users'];
$providerResult = $conn->query("SELECT COUNT(*) AS total_providers FROM service_providers");
$providerData = $providerResult->fetch_assoc();
$totalProviders = $providerData['total_providers'];
$serviceResult = $conn->query("SELECT COUNT(*) AS total_services FROM services");
$serviceData = $serviceResult->fetch_assoc();
$totalServices = $serviceData['total_services'];
$bookingResult = $conn->query("SELECT COUNT(*) AS total_bookings FROM bookings");
$bookingData = $bookingResult->fetch_assoc();
$totalBookings = $bookingData['total_bookings'];
$reviewResult = $conn->query("SELECT COUNT(*) AS total_reviews FROM reviews");
$reviewData = $reviewResult->fetch_assoc();
$totalReviews = $reviewData['total_reviews'];
$contactResult = $conn->query("SELECT COUNT(*) AS total_contacts FROM contact");
$contactData = $contactResult->fetch_assoc();
$totalContacts = $contactData['total_contacts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HomeGenie</title>
    <style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f5f6fa;
    }

    .admin-container {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
    width: 240px;
    background-color: #1f2937;
    padding: 25px 15px;
}

.sidebar h2 {
    color: white;
    text-align: center;
    margin-bottom: 35px;
    font-size: 24px;
}

.sidebar nav a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 13px 15px;
    margin-bottom: 8px;
    border-radius: 6px;
    transition: 0.3s;
}

.sidebar nav a:hover {
    background-color: #374151;
}

    .main-content {
        flex: 1;
        padding: 30px;
    }

    .main-content h1 {
    margin-bottom: 30px;
    font-size: 30px;
    color: #1f2937;
}

    .stat-card {
    background-color: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
}

.stat-card h3 {
    margin-bottom: 12px;
    color: #555;
    font-size: 16px;
    font-weight: 500;
}

.stat-card p {
    font-size: 32px;
    font-weight: bold;
    color: #1f2937;
}

    @media (max-width: 768px) {

    .admin-container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

}
</style>
</head>

<body>

   <body>

    <div class="admin-container">

        <aside class="sidebar">

            <h2>HomeGenie</h2>

            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php">Manage Users</a>
                <a href="providers.php">Manage Providers</a>
                <a href="services.php">Manage Services</a>
                <a href="bookings.php">Manage Bookings</a>
                <a href="reviews.php">Manage Reviews</a>
                <a href="contact.php">Manage Contacts</a>
            </nav>

        </aside>


        <main class="main-content">

            <h1>Admin Dashboard</h1>
            <p class="dashboard-subtitle">
    Welcome to the HomeGenie administration panel.
</p>

            <div class="stats-container">

                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsers; ?></p>
                </div>

                <div class="stat-card">
                    <h3>Total Providers</h3>
                    <p><?php echo $totalProviders; ?></p>
                </div>

                <div class="stat-card">
                    <h3>Total Services</h3>
                    <p><?php echo $totalServices; ?></p>
                </div>

                <div class="stat-card">
                    <h3>Total Bookings</h3>
                    <p><?php echo $totalBookings; ?></p>
                </div>

                <div class="stat-card">
                    <h3>Total Reviews</h3>
                    <p><?php echo $totalReviews; ?></p>
                </div>

                <div class="stat-card">
                    <h3>Total Contacts</h3>
                    <p><?php echo $totalContacts; ?></p>
                </div>

            </div>

        </main>

    </div>

</body>
</body>
</html>