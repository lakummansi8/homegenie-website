<?php
include '../../php/db.php';

$userResult = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - HomeGenie Admin</title>
</head>

<body>

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

</body>
</html>