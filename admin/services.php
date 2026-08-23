<?php
include '../config/db.php';

$serviceResult = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - HomeGenie Admin</title>
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

    <h1>Services</h1>

    <table border="1">
        <tr>
            <th>Service ID</th>
            <th>Service Name</th>
            <th>Category Id</th>
            <th>Description</th>
            <th>Price</th>
        </tr>

        <?php
        while ($service = $serviceResult->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $service['service_id']; ?></td>
                <td><?php echo $service['service_name']; ?></td>
                <td><?php echo $service['category_id']; ?></td>
                <td><?php echo $service['description']; ?></td>
                <td><?php echo $service['price']; ?></td>
            </tr>
        <?php
        }
        ?>

    </table>
</div>
</body>
</html>