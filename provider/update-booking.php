```php id="e8w4qk"
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";


/* Check request method */

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: bookings.php");
    exit;
}


$bookingId = $_POST["booking_id"] ?? "";
$action = $_POST["action"] ?? "";

$providerId = $_SESSION["provider_id"];


/* Check booking ID and action */

if ($bookingId == "" || !is_numeric($bookingId) || $action == "") {

    header("Location: bookings.php");
    exit;
}

$bookingId = (int)$bookingId;


/* Get booking status */

$sql = "SELECT booking_status
        FROM bookings
        WHERE booking_id = $bookingId
        AND provider_id = $providerId";

$result = mysqli_query($conn, $sql);

$booking = mysqli_fetch_assoc($result);


/* Check booking */

if (!$booking) {

    header("Location: bookings.php");
    exit;
}


/* Get current status */

$currentStatus = trim($booking["booking_status"] ?? "");

if ($currentStatus == "") {

    $currentStatus = "Pending";
}


/* Decide new status */

$newStatus = "";


if ($currentStatus == "Pending") {

    if ($action == "accept") {

        $newStatus = "Accepted";

    } elseif ($action == "reject") {

        $newStatus = "Rejected";
    }


} elseif ($currentStatus == "Accepted") {

    if ($action == "complete") {

        $newStatus = "Completed";

    } elseif ($action == "cancel") {

        $newStatus = "Cancelled";
    }
}


/* Check valid action */

if ($newStatus == "") {

    header("Location: bookings.php");
    exit;
}


/* Protect status value */

$newStatus = mysqli_real_escape_string($conn, $newStatus);


/* Update booking status */

if (
    $booking["booking_status"] === null ||
    trim($booking["booking_status"]) == ""
) {

    $sql = "UPDATE bookings SET
            booking_status = '$newStatus'
            WHERE booking_id = $bookingId
            AND provider_id = $providerId
            AND (booking_status IS NULL OR booking_status = '')";

} else {

    $currentStatus = mysqli_real_escape_string(
        $conn,
        $currentStatus
    );

    $sql = "UPDATE bookings SET
            booking_status = '$newStatus'
            WHERE booking_id = $bookingId
            AND provider_id = $providerId
            AND booking_status = '$currentStatus'";
}


mysqli_query($conn, $sql);


/* Go back to bookings page */

header("Location: bookings.php?updated=1");
exit;
?>
