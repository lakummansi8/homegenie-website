<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$service_id = $_GET["service_id"] ?? "";

if ($service_id === "") {
    header("Location: services.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT service_id, provider_id, service_name, price
     FROM services
     WHERE service_id = ? AND service_status = 'Active'"
);

$stmt->bind_param("i", $service_id);
$stmt->execute();

$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    header("Location: services.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Service - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <h2>Book Service</h2>

    <div class="service-card">

        <h3>
            <?php echo htmlspecialchars($service["service_name"]); ?>
        </h3>

        <p>
            <strong>Price:</strong>
            ₹<?php echo htmlspecialchars($service["price"]); ?>
        </p>

        <br>

        <form action="booking-process.php" method="POST">

            <input type="hidden" name="service_id"
                   value="<?php echo $service["service_id"]; ?>">

            <input type="hidden" name="provider_id"
                   value="<?php echo $service["provider_id"]; ?>">

            <label>Booking Date</label><br>
            <input type="date" name="booking_date" required><br><br>

            <label>Booking Time</label><br>
            <input type="time" name="booking_time" required><br><br>

            <label>Booking Address</label><br>
            <textarea name="booking_address" required></textarea><br><br>

            <button type="submit">Confirm Booking</button>

        </form>

    </div>

    <a href="services.php">Back to Services</a>

</body>
</html>