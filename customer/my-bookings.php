<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


$userId = $_SESSION["user_id"];


/* Get Customer Bookings */

$q = "select * from bookings
      where user_id = $userId
      order by created_at desc";

$result = mysqli_query($conn, $q);


$pageTitle = "My Bookings";

$pageCss = "bookings.css";


ob_start();

?>


<div class="customer-bookings">


    <!-- Page Heading -->

    <div class="bookings-heading">

        <h1>
            My Bookings
        </h1>

        <p>
            View and manage your service bookings.
        </p>

    </div>


    <!-- Success Message -->

    <?php

    if (
        isset($_GET["booking"]) &&
        $_GET["booking"] == "success"
    )
    {

    ?>

        <div class="success-message">

            Booking created successfully!

        </div>

    <?php

    }


    if (mysqli_num_rows($result) > 0)
    {

    ?>


        <!-- Booking List -->

        <div class="booking-list">


            <?php

            while ($booking = mysqli_fetch_array($result))
            {


                $serviceId =
                    $booking["service_id"];


                $providerId =
                    $booking["provider_id"];


                /* Get Service */

                $q2 = "select * from services
                       where service_id = $serviceId";

                $serviceResult =
                    mysqli_query($conn, $q2);


                $service =
                    mysqli_fetch_array(
                        $serviceResult
                    );


                /* Get Provider */

                $q3 = "select * from service_providers
                       where provider_id = $providerId";

                $providerResult =
                    mysqli_query($conn, $q3);


                $provider =
                    mysqli_fetch_array(
                        $providerResult
                    );


            ?>


                <div class="booking-item">


                    <!-- Booking Header -->

                    <div class="booking-header">


                        <div class="booking-service">

                            <span>
                                Service
                            </span>

                            <h2>
                                <?php

                                echo htmlspecialchars(
                                    $service["service_name"]
                                );

                                ?>
                            </h2>


                            <p>

                                Provider:

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $provider["full_name"]
                                    );

                                    ?>

                                </strong>

                            </p>

                        </div>


                        <!-- Status -->

                        <div class="booking-status">

                            <span>
                                Status
                            </span>

                            <strong>
                                <?php

                                echo htmlspecialchars(
                                    $booking["booking_status"]
                                );

                                ?>
                            </strong>

                        </div>


                    </div>


                    <!-- Booking Details -->

                    <div class="booking-details">


                        <div class="booking-detail">

                            <span>
                                Price
                            </span>

                            <p>

                                ₹<?php

                                echo number_format(
                                    $service["price"],
                                    2
                                );

                                ?>

                            </p>

                        </div>


                        <div class="booking-detail">

                            <span>
                                Date
                            </span>

                            <p>

                                <?php

                                echo htmlspecialchars(
                                    $booking["booking_date"]
                                );

                                ?>

                            </p>

                        </div>


                        <div class="booking-detail">

                            <span>
                                Time
                            </span>

                            <p>

                                <?php

                                echo htmlspecialchars(
                                    $booking["booking_time"]
                                );

                                ?>

                            </p>

                        </div>


                        <div class="booking-detail">

                            <span>
                                Address
                            </span>

                            <p>

                                <?php

                                echo htmlspecialchars(
                                    $booking["booking_address"]
                                );

                                ?>

                            </p>

                        </div>


                    </div>


                    <!-- Booking Actions -->

                    <?php

                    if (
                        $booking["booking_status"] == "Pending"
                    )
                    {

                    ?>

                        <div class="booking-actions">

                            <a
                                href="cancel-booking.php?booking_id=<?php echo $booking["booking_id"]; ?>"
                                class="cancel-button"
                            >
                                Cancel Booking
                            </a>

                        </div>

                    <?php

                    }

                    ?>


                </div>


            <?php

            }

            ?>


        </div>


    <?php

    }

    else

    {

    ?>


        <!-- Empty State -->

        <div class="empty-state">


            <h2>
                No Bookings Yet
            </h2>


            <p>
                You have not made any bookings yet.
            </p>


            <a
                href="services.php"
                class="browse-button"
            >
                Browse Services
            </a>


        </div>


    <?php

    }


    ?>


</div>


<?php

$pageContent = ob_get_clean();

require_once "layout/customer-layout.php";

?>