<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


$userId = $_SESSION["user_id"];


/* Get Form Data */

$serviceId = $_POST["service_id"];
$providerId = $_POST["provider_id"];
$bookingDate = $_POST["booking_date"];
$bookingTime = $_POST["booking_time"];
$bookingAddress = $_POST["booking_address"];


/* Check Empty Fields */

if (
    $serviceId == "" ||
    $providerId == "" ||
    $bookingDate == "" ||
    $bookingTime == "" ||
    $bookingAddress == ""
)
{
    header("Location: services.php?error=empty");
    exit;
}


/* Insert Booking */

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
          $userId,
          $providerId,
          $serviceId,
          '$bookingDate',
          '$bookingTime',
          '$bookingAddress',
          'Pending'
      )";


$result = mysqli_query($conn, $q);


if ($result)
{
    header("Location: my-bookings.php?booking=success");
    exit;
}


header("Location: services.php?error=failed");

?>