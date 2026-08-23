<?php

session_start();

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION CHECK
|--------------------------------------------------------------------------
| Only logged-in administrators can access the dashboard.
*/

if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: ../auth/login.html");
    exit;
}


/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

include "../config/db.php";


/*
|--------------------------------------------------------------------------
| ADMIN INFORMATION FROM SESSION
|--------------------------------------------------------------------------
*/

$adminName = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";


/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/


// Total Users
$userResult = $conn->query(
    "SELECT COUNT(*) AS total_users FROM users"
);

$userData = $userResult->fetch_assoc();

$totalUsers = $userData["total_users"];


// Total Providers
$providerResult = $conn->query(
    "SELECT COUNT(*) AS total_providers FROM service_providers"
);

$providerData = $providerResult->fetch_assoc();

$totalProviders = $providerData["total_providers"];


// Total Services
$serviceResult = $conn->query(
    "SELECT COUNT(*) AS total_services FROM services"
);

$serviceData = $serviceResult->fetch_assoc();

$totalServices = $serviceData["total_services"];


// Total Bookings
$bookingResult = $conn->query(
    "SELECT COUNT(*) AS total_bookings FROM bookings"
);

$bookingData = $bookingResult->fetch_assoc();

$totalBookings = $bookingData["total_bookings"];


// Total Reviews
$reviewResult = $conn->query(
    "SELECT COUNT(*) AS total_reviews FROM reviews"
);

$reviewData = $reviewResult->fetch_assoc();

$totalReviews = $reviewData["total_reviews"];


// Total Contact Messages
$contactResult = $conn->query(
    "SELECT COUNT(*) AS total_contacts FROM contact"
);

$contactData = $contactResult->fetch_assoc();

$totalContacts = $contactData["total_contacts"];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - HomeGenie</title>


    <!-- Main Website CSS -->

    <link
        rel="stylesheet"
        href="../css/style.css"
    >


    <!-- Admin Panel CSS -->

    <link
        rel="stylesheet"
        href="../css/admin-sidebar.css"
    >

</head>


<body>


<div class="admin-container">


    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <aside class="sidebar">


        <!-- Sidebar Brand -->

        <div class="sidebar-brand">

            <h2>HomeGenie</h2>

            <span>Admin Panel</span>

        </div>


        <!-- Navigation -->

        <nav>

            <a
                href="dashboard.php"
                class="active"
            >
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


            <a href="bookings.php">
                Manage Bookings
            </a>


            <a href="reviews.php">
                Manage Reviews
            </a>


            <a href="contact.php">
                Manage Contacts
            </a>

        </nav>


        <!-- Logout -->

        <div class="logout-button">

            <a href="../auth/logout.php">
                Logout
            </a>

        </div>


    </aside>



    <!-- =========================================================
         MAIN ADMIN AREA
    ========================================================== -->

    <main class="admin-main">


        <!-- =====================================================
             TOP NAVBAR
        ====================================================== -->

        <header class="admin-navbar">


            <!-- Page Title -->

            <div class="navbar-left">

                <h1>
                    Admin Dashboard
                </h1>

            </div>



            <!-- Admin Profile -->

            <div class="admin-profile">


                <!-- Avatar -->

                <div class="admin-avatar">

                    <?php

                    echo strtoupper(
                        substr($adminName, 0, 1)
                    );

                    ?>

                </div>


                <!-- Admin Details -->

                <div class="admin-info">

                    <strong>

                        <?php

                        echo htmlspecialchars(
                            $adminName
                        );

                        ?>

                    </strong>


                    <span>

                        <?php

                        echo htmlspecialchars(
                            $adminEmail
                        );

                        ?>

                    </span>

                </div>


            </div>


        </header>



        <!-- =====================================================
             DASHBOARD CONTENT
        ====================================================== -->

        <section class="admin-content">


            <h2>
                Overview
            </h2>


            <p class="dashboard-subtitle">

                Welcome to the HomeGenie
                administration panel.

            </p>



            <!-- =================================================
                 STATISTICS
            ================================================== -->

            <div class="stats-container">


                <!-- USERS -->

                <div class="stat-card">

                    <h3>
                        Total Users
                    </h3>

                    <p>
                        <?php echo $totalUsers; ?>
                    </p>

                </div>



                <!-- PROVIDERS -->

                <div class="stat-card">

                    <h3>
                        Total Providers
                    </h3>

                    <p>
                        <?php echo $totalProviders; ?>
                    </p>

                </div>



                <!-- SERVICES -->

                <div class="stat-card">

                    <h3>
                        Total Services
                    </h3>

                    <p>
                        <?php echo $totalServices; ?>
                    </p>

                </div>



                <!-- BOOKINGS -->

                <div class="stat-card">

                    <h3>
                        Total Bookings
                    </h3>

                    <p>
                        <?php echo $totalBookings; ?>
                    </p>

                </div>



                <!-- REVIEWS -->

                <div class="stat-card">

                    <h3>
                        Total Reviews
                    </h3>

                    <p>
                        <?php echo $totalReviews; ?>
                    </p>

                </div>



                <!-- CONTACTS -->

                <div class="stat-card">

                    <h3>
                        Total Contacts
                    </h3>

                    <p>
                        <?php echo $totalContacts; ?>
                    </p>

                </div>


            </div>


        </section>


    </main>


</div>


</body>

</html>