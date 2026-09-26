<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$userId = $_SESSION["user_id"];


/* Get Customer */

$q = "select * from users where user_id = $userId";
$res = mysqli_query($conn, $q);

$user = mysqli_fetch_array($res);

$userName = $user["full_name"];


/* Total Bookings */

$q = "select * from bookings where user_id = $userId";
$res = mysqli_query($conn, $q);

$totalBookings = mysqli_num_rows($res);


/* Pending Bookings */

$q = "select * from bookings
      where user_id = $userId
      and booking_status = 'Pending'";

$res = mysqli_query($conn, $q);

$pendingBookings = mysqli_num_rows($res);


/* Completed Bookings */

$q = "select * from bookings
      where user_id = $userId
      and booking_status = 'Completed'";

$res = mysqli_query($conn, $q);

$completedBookings = mysqli_num_rows($res);


/* Cancelled Bookings */

$q = "select * from bookings
      where user_id = $userId
      and booking_status = 'Cancelled'";

$res = mysqli_query($conn, $q);

$cancelledBookings = mysqli_num_rows($res);


$pageTitle = "Customer Dashboard";

$pageCss = "dashboard.css";


ob_start();

?>


<div class="customer-dashboard">


    <!-- Welcome -->

    <div class="dashboard-heading">

        <h1>
            Welcome, <?php echo htmlspecialchars($userName); ?>
        </h1>

        <p>
            Manage your services, bookings and profile from here.
        </p>

    </div>


    <!-- Booking Summary -->

    <div class="dashboard-card">

        <div class="dashboard-card-header">

            <h2>
                Booking Summary
            </h2>

        </div>


        <div class="booking-summary">


            <div class="summary-box">

                <p>
                    Total Bookings
                </p>

                <h3>
                    <?php echo $totalBookings; ?>
                </h3>

            </div>


            <div class="summary-box">

                <p>
                    Pending Bookings
                </p>

                <h3>
                    <?php echo $pendingBookings; ?>
                </h3>

            </div>


            <div class="summary-box">

                <p>
                    Completed Bookings
                </p>

                <h3>
                    <?php echo $completedBookings; ?>
                </h3>

            </div>


            <div class="summary-box">

                <p>
                    Cancelled Bookings
                </p>

                <h3>
                    <?php echo $cancelledBookings; ?>
                </h3>

            </div>


        </div>

    </div>


    <!-- My Information -->

    <div class="dashboard-card">

        <div class="dashboard-card-header">

            <h2>
                My Information
            </h2>


            <a
                href="profile.php"
                class="edit-button"
            >
                Edit Profile
            </a>

        </div>


        <div class="information">


            <div class="information-row">


                <div class="information-item">

                    <label>
                        Full Name
                    </label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $user["full_name"]
                        );
                        ?>
                    </p>

                </div>


                <div class="information-item">

                    <label>
                        Email
                    </label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $user["email"]
                        );
                        ?>
                    </p>

                </div>


                <div class="information-item">

                    <label>
                        Phone
                    </label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $user["phone"]
                        );
                        ?>
                    </p>

                </div>


            </div>


            <div class="information-row">


                <div class="information-item">

                    <label>
                        City
                    </label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $user["city"]
                        );
                        ?>
                    </p>

                </div>


                <div class="information-item">

                    <label>
                        Account Status
                    </label>

                    <p class="status">
                        <?php
                        echo htmlspecialchars(
                            $user["account_status"]
                        );
                        ?>
                    </p>

                </div>


                <div class="information-item">

                    <label>
                        Address
                    </label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $user["address"]
                        );
                        ?>
                    </p>

                </div>


            </div>


        </div>

    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "layout/customer-layout.php";

?>