<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT full_name, email, phone, address, city
     FROM users
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <h2>My Profile</h2>

    <div class="profile-card">

        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($user["full_name"]); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($user["email"]); ?>
        </p>

        <p>
            <strong>Phone:</strong>
            <?php echo htmlspecialchars($user["phone"]); ?>
        </p>

        <p>
            <strong>Address:</strong>
            <?php echo htmlspecialchars($user["address"]); ?>
        </p>

        <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($user["city"]); ?>
        </p>

    </div>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>