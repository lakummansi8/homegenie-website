<?php
include '../../php/db.php';

$reviewResult = $conn->query("SELECT * FROM reviews");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - HomeGenie Admin</title>
</head>

<body>

    <h1>Reviews</h1>

    <table border="1">

        <tr>
            <th>Review ID</th>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Provider ID</th>
            <th>Rating</th>
            <th>Review Comment</th>
            <th>Created At</th>
        </tr>

        <?php
        while ($review = $reviewResult->fetch_assoc()) {
        ?>

            <tr>
                <td><?php echo $review['review_id']; ?></td>
                <td><?php echo $review['booking_id']; ?></td>
                <td><?php echo $review['user_id']; ?></td>
                <td><?php echo $review['provider_id']; ?></td>
                <td><?php echo $review['rating']; ?></td>
                <td><?php echo $review['review_comment']; ?></td>
                <td><?php echo $review['created_at']; ?></td>
            </tr>

        <?php
        }
        ?>

    </table>

</body>
</html>