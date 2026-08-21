<?php
include '../../php/db.php';

$bookingResult = $conn->query("SELECT * FROM bookings");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - HomeGenie Admin</title>
</head>

<body>

    <h1>Bookings</h1>

    <table border="1">

        <tr>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Provider ID</th>
            <th>Service ID</th>
            <th>Booking Date</th>
            <th>Booking Time</th>
            <th>Booking Address</th>
            <th>Booking Status</th>
        </tr>

        <?php
        while ($booking = $bookingResult->fetch_assoc()) {
        ?>

            <tr>
                <td><?php echo $booking['booking_id']; ?></td>
                <td><?php echo $booking['user_id']; ?></td>
                <td><?php echo $booking['provider_id']; ?></td>
                <td><?php echo $booking['service_id']; ?></td>
                <td><?php echo $booking['booking_date']; ?></td>
                <td><?php echo $booking['booking_time']; ?></td>
                <td><?php echo $booking['booking_address']; ?></td>
                <td><?php echo $booking['booking_status']; ?></td>
            </tr>

        <?php
        }
        ?>

    </table>

</body>
</html>