<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


$userId = $_SESSION["user_id"];

$bookingId = $_GET["booking_id"];


if ($bookingId == "")
{
    header("Location: my-bookings.php");
    exit;
}


/* Cancel Booking */

$q = "update bookings
      set booking_status = 'Cancelled'
      where booking_id = $bookingId
      and user_id = $userId
      and booking_status = 'Pending'";


$result = mysqli_query($conn, $q);


/* Go Back to My Bookings */

header("Location: my-bookings.php");
exit;

?>