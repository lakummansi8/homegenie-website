<?php

$pageTitle = "Dashboard";
$pageCss = "dashboard.css";

$assetPath = "../";
$adminPath = "";

require_once "../config/db.php";


/* Total Customers */

$q = "select count(*) as count from users";
$res = mysqli_query($conn, $q);
$row = mysqli_fetch_array($res);

$totalUsers = $row["count"];


/* Total Providers */

$q = "select count(*) as count from service_providers";
$res = mysqli_query($conn, $q);
$row = mysqli_fetch_array($res);

$totalProviders = $row["count"];


/* Total Services */

$q = "select count(*) as count from services";
$res = mysqli_query($conn, $q);
$row = mysqli_fetch_array($res);

$totalServices = $row["count"];


/* Total Bookings */

$q = "select count(*) as count from bookings";
$res = mysqli_query($conn, $q);
$row = mysqli_fetch_array($res);

$totalBookings = $row["count"];


/* Recent Bookings */

$q = "select booking_id, user_id, provider_id, booking_date, booking_status
      from bookings
      order by booking_id desc
      limit 5";

$recentBookings = mysqli_query($conn, $q);


ob_start();

?>


<div class="dashboard-header">

    <div>

        <p class="dashboard-label">
            Admin Dashboard
        </p>

        <h2>
            Dashboard
        </h2>

        <p class="dashboard-text">
            Welcome to the HomeGenie Admin Dashboard. Here you can monitor platform activity.
        </p>

    </div>


    <div class="dashboard-buttons">

        <a
            href="<?php echo $adminPath; ?>pages/services/add-service.php"
            class="add-button"
        >
            + Add Service
        </a>


        <a
            href="<?php echo $adminPath; ?>pages/service-providers/add-service-provider.php"
            class="other-button"
        >
            + Add Provider
        </a>

    </div>

</div>


<!-- STATISTICS -->

<div class="stats-container">


    <a
        href="<?php echo $adminPath; ?>pages/users/users.php"
        class="stat-card"
    >

        <div class="stat-icon users-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>

            <p>
                Total Customers
            </p>

            <h3>
                <?php echo $totalUsers; ?>
            </h3>

            <span>
                View Users →
            </span>

        </div>

    </a>


    <a
        href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
        class="stat-card"
    >

        <div class="stat-icon providers-icon">
            <i class="fa-solid fa-user-tie"></i>
        </div>

        <div>

            <p>
                Service Providers
            </p>

            <h3>
                <?php echo $totalProviders; ?>
            </h3>

            <span>
                Manage Providers →
            </span>

        </div>

    </a>


    <a
        href="<?php echo $adminPath; ?>pages/services/services.php"
        class="stat-card"
    >

        <div class="stat-icon services-icon">
            <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>

        <div>

            <p>
                Total Services
            </p>

            <h3>
                <?php echo $totalServices; ?>
            </h3>

            <span>
                Manage Services →
            </span>

        </div>

    </a>


    <a
        href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
        class="stat-card"
    >

        <div class="stat-icon bookings-icon">
            <i class="fa-solid fa-calendar-check"></i>
        </div>

        <div>

            <p>
                Total Bookings
            </p>

            <h3>
                <?php echo $totalBookings; ?>
            </h3>

            <span>
                View Bookings →
            </span>

        </div>

    </a>

</div>


<!-- RECENT BOOKINGS -->

<div class="recent-bookings">

    <div class="recent-header">

        <div>

            <p>
                Activity
            </p>

            <h3>
                Recent Bookings
            </h3>

        </div>


        <a
            href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
        >
            View All
        </a>

    </div>


    <?php if (mysqli_num_rows($recentBookings) == 0) { ?>

        <div class="no-bookings">

            <i class="fa-solid fa-calendar-xmark"></i>

            <h4>
                No bookings yet
            </h4>

            <p>
                There are no bookings available at the moment.
            </p>

        </div>

    <?php } else { ?>


        <div class="booking-table">

            <table>

                <thead>

                    <tr>

                        <th>
                            Sr. No.
                        </th>

                        <th>
                            Customer ID
                        </th>

                        <th>
                            Provider ID
                        </th>

                        <th>
                            Booking Date
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    $srNo = 1;

                    while ($booking = mysqli_fetch_array($recentBookings)) {

                    ?>

                        <tr>

                            <td>
                                <?php echo $srNo; ?>
                            </td>

                            <td>
                                <?php echo $booking["user_id"]; ?>
                            </td>

                            <td>
                                <?php echo $booking["provider_id"]; ?>
                            </td>

                            <td>
                                <?php echo $booking["booking_date"]; ?>
                            </td>

                            <td>

                                <?php

                                $status = strtolower($booking["booking_status"]);

                                ?>

                                <span class="booking-status <?php echo $status; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>

                            </td>

                        </tr>


                    <?php

                        $srNo++;

                    }

                    ?>

                </tbody>

            </table>

        </div>


    <?php } ?>

</div>


<?php

$pageContent = ob_get_clean();

require_once "layout/admin-layout.php";

?>