<?php

$pageTitle = "Bookings";
$pageCss = "bookings.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$q = "select * from bookings order by booking_id desc";
$res = mysqli_query($conn, $q);


if (!$res)
{
    die("Booking query failed.");
}


ob_start();

?>


<div class="bookings-page">


    <div class="bookings-header">

        <div class="bookings-heading">

            <span class="bookings-eyebrow">
                Booking Management
            </span>

            <h2>
                Bookings
            </h2>

            <p>
                Manage customer bookings, service providers, appointments and booking status.
            </p>

        </div>


        <div class="bookings-header-actions">

            <a
                href="add-booking.php"
                class="bookings-primary-button"
            >
                + Add Booking
            </a>

        </div>


    </div>


    <?php if (isset($_GET["success"])) { ?>

        <div class="bookings-alert bookings-alert-success">

            <?php

            if ($_GET["success"] == "booking_added")
            {
                echo "Booking added successfully.";
            }
            elseif ($_GET["success"] == "booking_updated")
            {
                echo "Booking updated successfully.";
            }
            elseif ($_GET["success"] == "booking_deleted")
            {
                echo "Booking deleted successfully.";
            }

            ?>

        </div>

    <?php } ?>


    <?php if (isset($_GET["error"])) { ?>

        <div class="bookings-alert bookings-alert-error">

            <?php

            if ($_GET["error"] == "invalid_booking")
            {
                echo "Invalid booking ID.";
            }
            elseif ($_GET["error"] == "booking_not_found")
            {
                echo "Booking not found.";
            }
            elseif ($_GET["error"] == "delete_failed")
            {
                echo "Unable to delete booking. Please try again.";
            }
            else
            {
                echo "Something went wrong.";
            }

            ?>

        </div>

    <?php } ?>


    <div class="bookings-card">


        <div class="bookings-card-header">

            <strong>
                All Bookings
            </strong>

            <span class="bookings-count">

                <?php echo mysqli_num_rows($res); ?>

                Bookings

            </span>

        </div>


        <!-- Search -->

        <div class="admin-search">

            <input
                type="text"
                id="bookingSearch"
                placeholder="Search bookings..."
            >

        </div>


        <?php if (mysqli_num_rows($res) == 0) { ?>


            <div class="bookings-empty">

                <h4>
                    No Bookings Found
                </h4>

                <p>
                    There are currently no bookings available.
                </p>

                <a
                    href="add-booking.php"
                    class="bookings-empty-button"
                >
                    Add Booking
                </a>

            </div>


        <?php } else { ?>


            <div class="bookings-table-wrapper">

                <table class="bookings-table">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Service
                            </th>

                            <th>
                                Provider
                            </th>

                            <th>
                                Date & Time
                            </th>

                            <th>
                                Address
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        $srno = 1;


                        while ($row = mysqli_fetch_array($res))
                        {

                            $userId = $row["user_id"];

                            $q1 = "select * from users where user_id = $userId";
                            $res1 = mysqli_query($conn, $q1);

                            $user = mysqli_fetch_array($res1);


                            $providerId = $row["provider_id"];

                            $q2 = "select * from service_providers where provider_id = $providerId";
                            $res2 = mysqli_query($conn, $q2);

                            $provider = mysqli_fetch_array($res2);


                            $serviceId = $row["service_id"];

                            $q3 = "select * from services where service_id = $serviceId";
                            $res3 = mysqli_query($conn, $q3);

                            $service = mysqli_fetch_array($res3);

                        ?>


                            <tr class="booking-search-row">


                                <td>

                                    <?php echo $srno; ?>

                                </td>


                                <td>

                                    <?php

                                    if ($user)
                                    {
                                        echo htmlspecialchars($user["full_name"]);
                                    }
                                    else
                                    {
                                        echo "Customer not found";
                                    }

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    if ($service)
                                    {
                                        echo htmlspecialchars($service["service_name"]);
                                    }
                                    else
                                    {
                                        echo "Service not found";
                                    }

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    if ($provider)
                                    {
                                        echo htmlspecialchars($provider["full_name"]);
                                    }
                                    else
                                    {
                                        echo "Provider not found";
                                    }

                                    ?>

                                </td>


                                <td>

                                    <strong>
                                        <?php echo htmlspecialchars($row["booking_date"]); ?>
                                    </strong>

                                    <br>

                                    <?php echo htmlspecialchars($row["booking_time"]); ?>

                                </td>


                                <td>

                                    <?php echo htmlspecialchars($row["booking_address"]); ?>

                                </td>


                                <td>


                                    <?php if ($row["booking_status"] == "Pending") { ?>

                                        <span class="booking-status booking-pending">
                                            Pending
                                        </span>


                                    <?php } elseif ($row["booking_status"] == "Confirmed") { ?>

                                        <span class="booking-status booking-confirmed">
                                            Confirmed
                                        </span>


                                    <?php } elseif ($row["booking_status"] == "Completed") { ?>

                                        <span class="booking-status booking-completed">
                                            Completed
                                        </span>


                                    <?php } elseif ($row["booking_status"] == "Cancelled") { ?>

                                        <span class="booking-status booking-cancelled">
                                            Cancelled
                                        </span>


                                    <?php } else { ?>

                                        <span class="booking-status booking-other">
                                            <?php echo htmlspecialchars($row["booking_status"]); ?>
                                        </span>

                                    <?php } ?>


                                </td>


                                <td>

                                    <?php echo htmlspecialchars($row["created_at"]); ?>

                                </td>


                                <td>


                                    <div class="booking-actions">

                                        <a
                                            href="edit-booking.php?id=<?php echo $row["booking_id"]; ?>"
                                            class="booking-action booking-edit"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="delete-booking.php?id=<?php echo $row["booking_id"]; ?>"
                                            class="booking-action booking-delete"
                                            onclick="return confirm('Are you sure you want to delete this booking?');"
                                        >
                                            Delete
                                        </a>

                                    </div>


                                </td>


                            </tr>


                        <?php

                            $srno++;

                        }

                        ?>


                    </tbody>


                </table>


                <!-- No Search Result -->

                <div
                    id="bookingNoResult"
                    class="admin-no-result"
                >
                    No bookings found.
                </div>


            </div>


        <?php } ?>


    </div>


</div>


<!-- Booking Search -->

<script>

var searchInput =
    document.getElementById("bookingSearch");

var rows =
    document.querySelectorAll(".booking-search-row");

var noResult =
    document.getElementById("bookingNoResult");


if (searchInput)
{

    searchInput.addEventListener("keyup", function()
    {

        var searchText =
            searchInput.value.toLowerCase();

        var found = false;


        rows.forEach(function(row)
        {

            var rowText =
                row.innerText.toLowerCase();


            if (rowText.includes(searchText))
            {

                row.style.display = "";

                found = true;

            }
            else
            {

                row.style.display = "none";

            }

        });


        if (noResult)
        {

            if (found)
            {
                noResult.style.display = "none";
            }
            else
            {
                noResult.style.display = "block";
            }

        }

    });

}


var bookingsTableWrapper =
    document.querySelector(".bookings-table-wrapper");


if (bookingsTableWrapper)
{

    var isDragging = false;

    var startX = 0;

    var scrollLeft = 0;


    bookingsTableWrapper.addEventListener(
        "mousedown",
        function(e)
        {

            isDragging = true;

            bookingsTableWrapper.classList.add("dragging");


            startX =
                e.pageX -
                bookingsTableWrapper.offsetLeft;


            scrollLeft =
                bookingsTableWrapper.scrollLeft;

        }
    );


    bookingsTableWrapper.addEventListener(
        "mousemove",
        function(e)
        {

            if (!isDragging)
            {
                return;
            }


            e.preventDefault();


            var x =
                e.pageX -
                bookingsTableWrapper.offsetLeft;


            var distance =
                (x - startX) * 1.5;


            bookingsTableWrapper.scrollLeft =
                scrollLeft - distance;

        }
    );


    bookingsTableWrapper.addEventListener(
        "mouseup",
        function()
        {

            isDragging = false;

            bookingsTableWrapper.classList.remove("dragging");

        }
    );


    bookingsTableWrapper.addEventListener(
        "mouseleave",
        function()
        {

            isDragging = false;

            bookingsTableWrapper.classList.remove("dragging");

        }
    );

}
</script>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>