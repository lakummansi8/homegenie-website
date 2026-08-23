<?php
include '../config/db.php';

$reviewResult = $conn->query("SELECT * FROM reviews");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - HomeGenie Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin-sidebar.css">
</head>

<body>
    <div class="admin-container">
     <aside class="sidebar">

            <h2>HomeGenie</h2>

            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php">Manage Users</a>
                <a href="providers.php">Manage Providers</a>
                <a href="services.php">Manage Services</a>
                <a href="bookings.php">Manage Bookings</a>
                <a href="reviews.php">Manage Reviews</a>
                <a href="contact.php">Manage Contacts</a>
                 <div class="logout-button">
                    <button>Logout</button>
                </div>
            </nav>

        </aside>

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
</div>
</body>
</html>