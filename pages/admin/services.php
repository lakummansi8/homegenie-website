<?php
include '../../php/db.php';

$serviceResult = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - HomeGenie Admin</title>
</head>

<body>

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

</body>
</html>