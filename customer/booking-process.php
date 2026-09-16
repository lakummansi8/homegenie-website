<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: services.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$service_id = $_POST["service_id"] ?? "";
$provider_id = $_POST["provider_id"] ?? "";
$booking_date = $_POST["booking_date"] ?? "";
$booking_time = $_POST["booking_time"] ?? "";
$booking_address = trim($_POST["booking_address"] ?? "");

if (
    $service_id === "" ||
    $provider_id === "" ||
    $booking_date === "" ||
    $booking_time === "" ||
    $booking_address === ""
) {
    header("Location: services.php?error=empty");
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO bookings
    (user_id, provider_id, service_id, booking_date, booking_time, booking_address, booking_status)
    VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
);

$stmt->bind_param(
    "iiisss",
    $user_id,
    $provider_id,
    $service_id,
    $booking_date,
    $booking_time,
    $booking_address
);

if ($stmt->execute()) {
    header("Location: my-bookings.php?booking=success");
    exit;
}

header("Location: services.php?error=failed");
exit;