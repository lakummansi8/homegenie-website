<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


/* Get Service ID */

$serviceId = $_GET["service_id"];


if ($serviceId == "")
{
    header("Location: services.php");
    exit;
}


/* Get Service */

$q = "select * from services
      where service_id = $serviceId
      and service_status = 'Active'";

$result = mysqli_query($conn, $q);


if (mysqli_num_rows($result) == 0)
{
    header("Location: services.php");
    exit;
}


$service = mysqli_fetch_array($result);


/* Get Provider */

$providerId = $service["provider_id"];


$q2 = "select * from service_providers
       where provider_id = $providerId";

$providerResult = mysqli_query($conn, $q2);


$provider = mysqli_fetch_array($providerResult);


$pageTitle = "Book Service";

$pageCss = "book-service.css";


ob_start();

?>


<div class="book-service-page">


    <!-- Page Heading -->

    <div class="booking-heading">

        <h1>
            Book a Service
        </h1>

        <p>
            Enter your booking details to request this service.
        </p>

    </div>


    <!-- Booking Card -->

    <div class="booking-card">


        <!-- Selected Service -->

        <div class="selected-service">

            <span>
                Selected Service
            </span>

            <h2>
                <?php

                echo htmlspecialchars(
                    $service["service_name"]
                );

                ?>
            </h2>


            <div class="service-provider">

                <span>
                    Service Provider
                </span>

                <strong>
                    <?php

                    echo htmlspecialchars(
                        $provider["full_name"]
                    );

                    ?>
                </strong>

            </div>


            <div class="service-price">

                <span>
                    Starting Price
                </span>

                <strong>
                    ₹<?php

                    echo number_format(
                        $service["price"],
                        2
                    );

                    ?>
                </strong>

            </div>


        </div>


        <!-- Booking Form -->

        <div class="booking-form">


            <form
                action="booking-process.php"
                method="POST"
            >


                <input
                    type="hidden"
                    name="service_id"
                    value="<?php echo $service["service_id"]; ?>"
                >


                <input
                    type="hidden"
                    name="provider_id"
                    value="<?php echo $service["provider_id"]; ?>"
                >


                <!-- Date -->

                <div class="form-field">

                    <label>
                        Booking Date
                    </label>

                    <input
                        type="date"
                        name="booking_date"
                        required
                    >

                </div>


                <!-- Time -->

                <div class="form-field">

                    <label>
                        Booking Time
                    </label>

                    <input
                        type="time"
                        name="booking_time"
                        required
                    >

                </div>


                <!-- Address -->

                <div class="form-field">

                    <label>
                        Booking Address
                    </label>

                    <textarea
                        name="booking_address"
                        required
                        placeholder="Enter the address where the service is required"
                    ></textarea>

                </div>


                <!-- Buttons -->

                <div class="form-actions">

                    <a
                        href="services.php"
                        class="back-button"
                    >
                        Back to Services
                    </a>


                    <button
                        type="submit"
                        class="confirm-button"
                    >
                        Confirm Booking
                    </button>

                </div>


            </form>


        </div>


    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "layout/customer-layout.php";

?>