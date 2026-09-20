<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: bookings.php");
    exit;
}

$bookingId = $_POST["booking_id"] ?? "";
$action = $_POST["action"] ?? "";

$providerId = $_SESSION["provider_id"];

if ($bookingId === "" || !is_numeric($bookingId) || $action === "") {
    header("Location: bookings.php");
    exit;
}

$bookingId = (int)$bookingId;

$stmt = $conn->prepare(
    "SELECT booking_status
     FROM bookings
     WHERE booking_id = ?
     AND provider_id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "ii",
    $bookingId,
    $providerId
);

$stmt->execute();

$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$stmt->close();

if (!$booking) {
    header("Location: bookings.php");
    exit;
}

$currentStatus = trim($booking["booking_status"] ?? "");

if ($currentStatus === "") {
    $currentStatus = "Pending";
}

$newStatus = "";

if ($currentStatus === "Pending") {

    if ($action === "accept") {

        $newStatus = "Accepted";

    } elseif ($action === "reject") {

        $newStatus = "Rejected";
    }

} elseif ($currentStatus === "Accepted") {

    if ($action === "complete") {

        $newStatus = "Completed";

    } elseif ($action === "cancel") {

        $newStatus = "Cancelled";
    }
}

if ($newStatus === "") {
    header("Location: bookings.php");
    exit;
}

if ($booking["booking_status"] === null || trim($booking["booking_status"]) === "") {

    $stmt = $conn->prepare(
        "UPDATE bookings
         SET booking_status = ?
         WHERE booking_id = ?
         AND provider_id = ?
         AND (booking_status IS NULL OR booking_status = '')"
    );

    $stmt->bind_param(
        "sii",
        $newStatus,
        $bookingId,
        $providerId
    );

} else {

    $stmt = $conn->prepare(
        "UPDATE bookings
         SET booking_status = ?
         WHERE booking_id = ?
         AND provider_id = ?
         AND booking_status = ?"
    );

    $stmt->bind_param(
        "siis",
        $newStatus,
        $bookingId,
        $providerId,
        $currentStatus
    );
}

$stmt->execute();

$stmt->close();

header("Location: bookings.php?updated=1");
exit;