<?php

$pageTitle = "Add Booking";
$pageCss = "bookings.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$userId = "";
$providerId = "";
$serviceId = "";
$bookingDate = "";
$bookingTime = "";
$bookingAddress = "";
$bookingStatus = "Pending";

$error = "";


if (isset($_POST["submit"]))
{

    $userId = $_POST["user_id"];
    $providerId = $_POST["provider_id"];
    $serviceId = $_POST["service_id"];
    $bookingDate = $_POST["booking_date"];
    $bookingTime = $_POST["booking_time"];
    $bookingAddress = $_POST["booking_address"];
    $bookingStatus = $_POST["booking_status"];


    if ($userId == "")
    {
        $error = "Please select a customer.";
    }
    elseif ($providerId == "")
    {
        $error = "Please select a service provider.";
    }
    elseif ($serviceId == "")
    {
        $error = "Please select a service.";
    }
    elseif ($bookingDate == "")
    {
        $error = "Please select a booking date.";
    }
    elseif ($bookingTime == "")
    {
        $error = "Please select a booking time.";
    }
    elseif ($bookingAddress == "")
    {
        $error = "Please enter the booking address.";
    }


    if ($error == "")
    {

        $q = "select * from users where user_id = $userId";

        $res = mysqli_query($conn, $q);


        if (mysqli_num_rows($res) == 0)
        {
            $error = "Customer not found.";
        }

    }


    if ($error == "")
    {

        $q = "select * from service_providers where provider_id = $providerId";

        $res = mysqli_query($conn, $q);


        if (mysqli_num_rows($res) == 0)
        {
            $error = "Service provider not found.";
        }

    }


    if ($error == "")
    {

        $q = "select * from services
              where service_id = $serviceId
              and provider_id = $providerId";

        $res = mysqli_query($conn, $q);


        if (mysqli_num_rows($res) == 0)
        {
            $error = "Selected service does not belong to this provider.";
        }

    }


    if ($error == "")
    {

        $q = "insert into bookings
        (
            user_id,
            provider_id,
            service_id,
            booking_date,
            booking_time,
            booking_address,
            booking_status
        )
        values
        (
            '$userId',
            '$providerId',
            '$serviceId',
            '$bookingDate',
            '$bookingTime',
            '$bookingAddress',
            '$bookingStatus'
        )";


        $res = mysqli_query($conn, $q);


        if ($res)
        {
            header("Location: bookings.php?success=booking_added");
            exit;
        }
        else
        {
            $error = "Unable to create booking.";
        }

    }

}


/* Get Customers */

$q1 = "select * from users where account_status = 'Active' order by full_name";

$res1 = mysqli_query($conn, $q1);


/* Get Providers */

$q2 = "select * from service_providers where account_status = 'Active' order by full_name";

$res2 = mysqli_query($conn, $q2);


/* Get Services */

$q3 = "select * from services where service_status = 'Active' order by service_name";

$res3 = mysqli_query($conn, $q3);


ob_start();

?>


<div class="bookings-page">


    <div class="bookings-header">

        <div class="bookings-heading">

            <span class="bookings-eyebrow">
                Booking Management
            </span>

            <h2>
                Add Booking
            </h2>

            <p>
                Create a new customer service booking.
            </p>

        </div>


        <div class="bookings-header-actions">

            <a
                href="bookings.php"
                class="bookings-secondary-button"
            >
                &larr; Back to Bookings
            </a>

        </div>

    </div>


    <?php if ($error != "") { ?>

        <div class="bookings-alert bookings-alert-error">

            <strong>
                Please fix the following:
            </strong>

            <ul>

                <li>
                    <?php echo htmlspecialchars($error); ?>
                </li>

            </ul>

        </div>

    <?php } ?>


    <div class="bookings-form-card">


        <div class="bookings-form-header">

            <strong>
                Booking Details
            </strong>

        </div>


        <div class="bookings-form-body">


            <form method="POST">


                <div class="bookings-form-row">


                    <div class="bookings-form-group">

                        <label>
                            Customer <span>*</span>
                        </label>

                        <select
                            name="user_id"
                            required
                        >

                            <option value="">
                                Select Customer
                            </option>


                            <?php while ($user = mysqli_fetch_array($res1)) { ?>

                                <option
                                    value="<?php echo $user["user_id"]; ?>"
                                    <?php
                                    if ($userId == $user["user_id"])
                                    {
                                        echo "selected";
                                    }
                                    ?>
                                >

                                    <?php echo htmlspecialchars($user["full_name"]); ?>

                                    -
                                    
                                    <?php echo htmlspecialchars($user["email"]); ?>

                                </option>

                            <?php } ?>


                        </select>

                    </div>


                    <div class="bookings-form-group">

                        <label>
                            Service Provider <span>*</span>
                        </label>

                        <select
                            name="provider_id"
                            id="provider_id"
                            required
                        >

                            <option value="">
                                Select Provider
                            </option>


                            <?php while ($provider = mysqli_fetch_array($res2)) { ?>

                                <option
                                    value="<?php echo $provider["provider_id"]; ?>"
                                    <?php
                                    if ($providerId == $provider["provider_id"])
                                    {
                                        echo "selected";
                                    }
                                    ?>
                                >

                                    <?php echo htmlspecialchars($provider["full_name"]); ?>

                                </option>

                            <?php } ?>


                        </select>

                    </div>


                </div>


                <div class="bookings-form-row">


                    <div class="bookings-form-group">

                        <label>
                            Service <span>*</span>
                        </label>

                        <select
                            name="service_id"
                            id="service_id"
                            required
                        >

                            <option value="">
                                Select Service
                            </option>


                            <?php while ($service = mysqli_fetch_array($res3)) { ?>

                                <option
                                    value="<?php echo $service["service_id"]; ?>"
                                    data-provider="<?php echo $service["provider_id"]; ?>"
                                    <?php
                                    if ($serviceId == $service["service_id"])
                                    {
                                        echo "selected";
                                    }
                                    ?>
                                >

                                    <?php echo htmlspecialchars($service["service_name"]); ?>

                                </option>

                            <?php } ?>


                        </select>

                    </div>


                    <div class="bookings-form-group">

                        <label>
                            Booking Status <span>*</span>
                        </label>

                        <select
                            name="booking_status"
                            required
                        >

                            <option
                                value="Pending"
                                <?php
                                if ($bookingStatus == "Pending")
                                {
                                    echo "selected";
                                }
                                ?>
                            >
                                Pending
                            </option>


                            <option
                                value="Confirmed"
                                <?php
                                if ($bookingStatus == "Confirmed")
                                {
                                    echo "selected";
                                }
                                ?>
                            >
                                Confirmed
                            </option>


                            <option
                                value="Completed"
                                <?php
                                if ($bookingStatus == "Completed")
                                {
                                    echo "selected";
                                }
                                ?>
                            >
                                Completed
                            </option>


                            <option
                                value="Cancelled"
                                <?php
                                if ($bookingStatus == "Cancelled")
                                {
                                    echo "selected";
                                }
                                ?>
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                </div>


                <div class="bookings-form-row">


                    <div class="bookings-form-group">

                        <label>
                            Booking Date <span>*</span>
                        </label>

                        <input
                            type="date"
                            name="booking_date"
                            value="<?php echo htmlspecialchars($bookingDate); ?>"
                            required
                        >

                    </div>


                    <div class="bookings-form-group">

                        <label>
                            Booking Time <span>*</span>
                        </label>

                        <input
                            type="time"
                            name="booking_time"
                            value="<?php echo htmlspecialchars($bookingTime); ?>"
                            required
                        >

                    </div>


                </div>


                <div class="bookings-form-group bookings-address-group">

                    <label>
                        Booking Address <span>*</span>
                    </label>

                    <textarea
                        name="booking_address"
                        rows="4"
                        required
                    ><?php echo htmlspecialchars($bookingAddress); ?></textarea>

                </div>


                <div class="bookings-form-actions">


                    <a
                        href="bookings.php"
                        class="bookings-cancel-button"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        name="submit"
                        class="bookings-submit-button"
                    >
                        Create Booking
                    </button>


                </div>


            </form>


        </div>


    </div>


</div>


<script src="<?php echo $assetPath; ?>js/admin/bookings.js"></script>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>