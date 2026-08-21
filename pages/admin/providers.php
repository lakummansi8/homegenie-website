<?php
include '../../php/db.php';

$providerResult = $conn->query("SELECT * FROM service_providers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Providers - HomeGenie Admin</title>
</head>

<body>

    <h1>Service Providers</h1>

    <table border="1">
        <tr>
            <th>Provider ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Experience</th>
            <th>Area</th>
            <th>City</th>
            <th>Availability</th>
            <th>Account Status</th>
        </tr>

        <?php
        while ($provider = $providerResult->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $provider['provider_id']; ?></td>
                <td><?php echo $provider['full_name']; ?></td>
                <td><?php echo $provider['email']; ?></td>
                <td><?php echo $provider['phone']; ?></td>
                <td><?php echo $provider['gender']; ?></td>
                <td><?php echo $provider['experience']; ?></td>
                <td><?php echo $provider['area']; ?></td>
                <td><?php echo $provider['city']; ?></td>
                <td><?php echo $provider['availability']; ?></td>
                <td><?php echo $provider['account_status']; ?></td>
            </tr>
        <?php
        }
        ?>

    </table>

</body>
</html>