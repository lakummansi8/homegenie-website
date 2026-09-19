<?php

require_once "../../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: bookings.php?error=invalid_booking");
    exit;
}


/* Delete related review */

$stmt = $conn->prepare(
    "DELETE FROM reviews WHERE booking_id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();


/* Delete booking */

$stmt = $conn->prepare(
    "DELETE FROM bookings WHERE booking_id = ?"
);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: bookings.php?success=booking_deleted");
    exit;
}

$stmt->close();

header("Location: bookings.php?error=delete_failed");
exit;
?>