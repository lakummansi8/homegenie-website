<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT
        b.booking_id,
        b.booking_date,
        b.booking_time,
        b.booking_address,
        b.booking_status,
        s.service_name,
        s.price,
        sp.full_name AS provider_name
     FROM bookings b
     JOIN services s ON b.service_id = s.service_id
     JOIN service_providers sp ON b.provider_id = sp.provider_id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <h2>My Bookings</h2>

    <?php if (isset($_GET["booking"]) && $_GET["booking"] === "success"): ?>

        <p>Booking created successfully!</p>

    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>

        <?php while ($booking = $result->fetch_assoc()): ?>

            <div class="booking-card">

                <h3>
                    <?php echo htmlspecialchars($booking["service_name"]); ?>
                </h3>

                <p>
                    <strong>Provider:</strong>
                    <?php echo htmlspecialchars($booking["provider_name"]); ?>
                </p>

                <p>
                    <strong>Price:</strong>
                    ₹<?php echo htmlspecialchars($booking["price"]); ?>
                </p>

                <p>
                    <strong>Date:</strong>
                    <?php echo htmlspecialchars($booking["booking_date"]); ?>
                </p>

                <p>
                    <strong>Time:</strong>
                    <?php echo htmlspecialchars($booking["booking_time"]); ?>
                </p>

                <p>
                    <strong>Address:</strong>
                    <?php echo htmlspecialchars($booking["booking_address"]); ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?php echo htmlspecialchars($booking["booking_status"]); ?>
                </p>

                <?php if ($booking["booking_status"] === "Pending"): ?>

                    <br>

                    <a href="cancel-booking.php?booking_id=<?php echo $booking["booking_id"]; ?>">
                        <button type="button">Cancel Booking</button>
                    </a>

                <?php endif; ?>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>You have not made any bookings yet.</p>

    <?php endif; ?>

    <br>

    <a href="services.php">Browse Services</a>

    <br><br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>