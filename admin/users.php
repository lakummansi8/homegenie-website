<?php
include '../config/db.php';

$userResult = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - HomeGenie Admin</title>
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

    <h1>Registered Users</h1>   
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
        </tr>

        <?php
        while ($user = $userResult->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['full_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['phone']; ?></td>
                <td><?php echo $user['address']; ?></td>
                <td><?php echo $user['city']; ?></td>
            </tr>
        <?php
        }
        ?>

    </table>

</body>
</div>
</body>
</html>