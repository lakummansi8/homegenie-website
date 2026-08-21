<?php
include '../../php/db.php';

$contactResult = $conn->query("SELECT * FROM contact");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - HomeGenie Admin</title>
</head>

<body>

    <h1>Contacts</h1>

    <table border="1">

        <tr>
            <th>Contact id</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Message Status</th>
              <th>Created At</th>
        </tr>

        <?php
        while ($contact = $contactResult->fetch_assoc()) {
        ?>

            <tr>
                <td><?php echo $contact['contact_id']; ?></td>
                <td><?php echo $contact['full_name']; ?></td>
                <td><?php echo $contact['email']; ?></td>
                <td><?php echo $contact['phone']; ?></td>
                <td><?php echo $contact['subject']; ?></td>
                <td><?php echo $contact['message']; ?></td>
                <td><?php echo $contact['message_status']; ?></td>
                <td><?php echo $contact['created_at']; ?></td>
            </tr>

        <?php
        }
        ?>

    </table>

</body>
</html>