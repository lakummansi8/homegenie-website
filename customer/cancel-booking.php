<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$booking_id = $_GET["booking_id"] ?? "";

if ($booking_id === "") {
    header("Location: my-bookings.php");
    exit;
}

$stmt = $conn->prepare(
    "UPDATE bookings
     SET booking_status = 'Cancelled'
     WHERE booking_id = ?
     AND user_id = ?
     AND booking_status = 'Pending'"
);

$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();

header("Location: my-bookings.php");
exit;