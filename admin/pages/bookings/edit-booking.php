<?php

$pageTitle = "Edit Booking";
$pageCss = "bookings.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$id = $_GET["id"];


if ($id == "")
{
    header("Location: bookings.php?error=invalid_booking");
    exit;
}


$q = "select * from bookings where booking_id = $id";

$res = mysqli_query($conn, $q);


if (mysqli_num_rows($res) == 0)
{
    header("Location: bookings.php?error=booking_not_found");
    exit;
}


$booking = mysqli_fetch_array($res);


$userId = $booking["user_id"];
$providerId = $booking["provider_id"];
$serviceId = $booking["service_id"];
$bookingDate = $booking["booking_date"];
$bookingTime = $booking["booking_time"];
$bookingAddress = $booking["booking_address"];
$bookingStatus = $booking["booking_status"];

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

        $q = "update bookings set

                user_id = '$userId',

                provider_id = '$providerId',

                service_id = '$serviceId',

                booking_date = '$bookingDate',

                booking_time = '$bookingTime',

                booking_address = '$bookingAddress',

                booking_status = '$bookingStatus'

                where booking_id = $id";


        $res = mysqli_query($conn, $q);


        if ($res)
        {
            header("Location: bookings.php?success=booking_updated");
            exit;
        }
        else
        {
            $error = "Unable to update booking.";
        }

    }

}


/* Get Customers */

$q1 = "select * from users where account_status = 'Active' order by full_name";

$customers = mysqli_query($conn, $q1);


/* Get Providers */

$q2 = "select * from service_providers where account_status = 'Active' order by full_name";

$providers = mysqli_query($conn, $q2);


/* Get Services */

$q3 = "select * from services where service_status = 'Active' order by service_name";

$services = mysqli_query($conn, $q3);


ob_start();

?>


<div class="bookings-page">


    <div class="bookings-header">

        <div class="bookings-heading">

            <span class="bookings-eyebrow">
                Booking Management
            </span>

            <h2>
                Edit Booking
            </h2>

            <p>
                Update the details of this customer booking.
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

            <span class="booking-number">
                Booking #<?php echo $id; ?>
            </span>

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


                            <?php while ($customer = mysqli_fetch_array($customers)) { ?>

                                <option
                                    value="<?php echo $customer["user_id"]; ?>"
                                    <?php
                                    if ($customer["user_id"] == $userId)
                                    {
                                        echo "selected";
                                    }
                                    ?>
                                >

                                    <?php echo htmlspecialchars($customer["full_name"]); ?>

                                    -

                                    <?php echo htmlspecialchars($customer["email"]); ?>

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


                            <?php while ($provider = mysqli_fetch_array($providers)) { ?>

                                <option
                                    value="<?php echo $provider["provider_id"]; ?>"
                                    <?php
                                    if ($provider["provider_id"] == $providerId)
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


                            <?php while ($service = mysqli_fetch_array($services)) { ?>

                                <option
                                    value="<?php echo $service["service_id"]; ?>"
                                    data-provider="<?php echo $service["provider_id"]; ?>"
                                    <?php
                                    if ($service["service_id"] == $serviceId)
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
                        Save Changes
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