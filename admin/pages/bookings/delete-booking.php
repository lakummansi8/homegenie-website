<?php

require_once "../../../config/db.php";

$id = $_GET['id'];

if($id == "")
{
    header("Location: bookings.php?error=invalid_booking");
    exit;
}

$q1 = "delete from reviews where booking_id = $id";
$res1 = mysqli_query($conn,$q1);

$q2 = "delete from bookings where booking_id = $id";
$res2 = mysqli_query($conn,$q2);

if($res2)
{
    header("Location: bookings.php?success=booking_deleted");
    exit;
}
else
{
    header("Location: bookings.php?error=delete_failed");
    exit;
}

?>