<?php

require_once "../../../config/db.php";

$id = $_GET['id'];

if($id == "")
{
    header("Location: categories.php");
    exit;
}


/* Check category */

$sql = "SELECT * FROM categories WHERE category_id = $id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0)
{
    header("Location: categories.php");
    exit;
}

$category = mysqli_fetch_array($result);


/* Get services of this category */

$sql = "SELECT service_id FROM services WHERE category_id = $id";
$services = mysqli_query($conn, $sql);


/* Delete related bookings and reviews */

while($service = mysqli_fetch_array($services))
{
    $serviceId = $service['service_id'];

    $sql = "SELECT booking_id FROM bookings WHERE service_id = $serviceId";
    $bookings = mysqli_query($conn, $sql);

    while($booking = mysqli_fetch_array($bookings))
    {
        $bookingId = $booking['booking_id'];

        mysqli_query($conn, "DELETE FROM reviews WHERE booking_id = $bookingId");
    }

    mysqli_query($conn, "DELETE FROM bookings WHERE service_id = $serviceId");
}


/* Delete services */

$sql = "DELETE FROM services WHERE category_id = $id";
$result = mysqli_query($conn, $sql);

if(!$result)
{
    echo "Services could not be deleted.";
    echo "<br>";
    echo mysqli_error($conn);
    exit;
}


/* Delete category */

$sql = "DELETE FROM categories WHERE category_id = $id";
$result = mysqli_query($conn, $sql);

if($result)
{
    /* Delete category image */

    if($category['category_image'] != "")
    {
        $image = "../../../assets/categories/" . $category['category_image'];

        if(file_exists($image))
        {
            unlink($image);
        }
    }

    header("Location: categories.php?success=category_deleted");
    exit;
}
else
{
    echo "Category could not be deleted.";
    echo "<br>";
    echo mysqli_error($conn);
}

?>