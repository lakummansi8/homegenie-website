<?php

require_once "../../../config/db.php";


$id = $_GET["id"] ?? "";


if ($id == "") {

    header("Location: categories.php");

    exit;

}


/* Check category */

$q = "select * from categories where category_id = $id";

$res = mysqli_query($conn, $q);


if (mysqli_num_rows($res) == 0) {

    header("Location: categories.php?error=category_not_found");

    exit;

}


$category = mysqli_fetch_array($res);


/* Get services */

$q = "select service_id from services where category_id = $id";

$services = mysqli_query($conn, $q);


/* Delete bookings and reviews */

while ($service = mysqli_fetch_array($services)) {


    $serviceId = $service["service_id"];


    $q = "select booking_id from bookings where service_id = $serviceId";

    $bookings = mysqli_query($conn, $q);


    while ($booking = mysqli_fetch_array($bookings)) {

        $bookingId = $booking["booking_id"];


        $q = "delete from reviews where booking_id = $bookingId";

        mysqli_query($conn, $q);

    }


    $q = "delete from bookings where service_id = $serviceId";

    mysqli_query($conn, $q);

}


/* Delete services */

$q = "delete from services where category_id = $id";

$res = mysqli_query($conn, $q);


if (!$res) {

    echo "Services could not be deleted.";

    echo "<br>";

    echo mysqli_error($conn);

    exit;

}


/* Delete category */

$q = "delete from categories where category_id = $id";

$res = mysqli_query($conn, $q);


if ($res) {


    /* Delete category image */

    if ($category["category_image"] != "") {


        $image =
            "../../../assets/categories/" .
            $category["category_image"];


        if (file_exists($image)) {

            unlink($image);

        }

    }


    header(
        "Location: categories.php?success=category_deleted"
    );

    exit;

}
else {

    echo "Category could not be deleted.";

    echo "<br>";

    echo mysqli_error($conn);

}

?>