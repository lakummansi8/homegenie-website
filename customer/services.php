<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/db.php";

$sql = "SELECT 
            s.service_id,
            s.service_name,
            s.description,
            s.price,
            sp.full_name AS provider_name,
            sp.area,
            sp.city
        FROM services s
        INNER JOIN service_providers sp
            ON s.provider_id = sp.provider_id
        WHERE sp.account_status = 'active'
        ORDER BY s.service_id DESC";

$result = $conn->query($sql);

$userName = $_SESSION["user_name"] ?? "Customer";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Services - HomeGenie</title>
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

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="services.php" class="active">
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

            <h2>Services</h2>

            <div class="header-user">
                <strong><?php echo htmlspecialchars($userName); ?></strong>
                <span>Customer</span>
            </div>

        </header>


        <div class="dashboard">

            <div class="welcome">

                <h1>Available Services</h1>

                <p>
                    Browse services offered by HomeGenie service providers.
                </p>

            </div>


            <div class="service-list">

                <?php if ($result->num_rows > 0) { ?>

                    <?php while ($service = $result->fetch_assoc()) { ?>

                        <div class="service-item">

                            <div class="service-details">

                                <h2>
                                    <?php echo htmlspecialchars($service["service_name"]); ?>
                                </h2>

                                <p class="service-description">
                                    <?php echo htmlspecialchars($service["description"]); ?>
                                </p>

                                <div class="service-info">

                                    <span>
                                        Provider:
                                        <strong>
                                            <?php echo htmlspecialchars($service["provider_name"]); ?>
                                        </strong>
                                    </span>

                                    <span>
                                        Area:
                                        <strong>
                                            <?php echo htmlspecialchars($service["area"]); ?>
                                        </strong>
                                    </span>

                                    <span>
                                        City:
                                        <strong>
                                            <?php echo htmlspecialchars($service["city"]); ?>
                                        </strong>
                                    </span>

                                </div>

                            </div>


                            <div class="service-price">

                                <span>Starting Price</span>

                                <strong>
                                    ₹<?php echo number_format($service["price"], 2); ?>
                                </strong>

                               <a href="book-service.php?service_id=<?php echo $service["service_id"]; ?>">
                                    Book Service
                                </a>

                            </div>

                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="empty-state">
                        <h2>No Services Available</h2>
                        <p>
                            There are currently no services available.
                        </p>
                    </div>

                <?php } ?>

            </div>

        </div>

    </main>

</div>

</body>
</html>