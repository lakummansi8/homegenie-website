<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query(
    "SELECT service_id, provider_id, service_name, description, price
     FROM services
     WHERE service_status = 'Active'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>HomeGenie Services</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <h2>Available Services</h2>

    <?php if ($result->num_rows > 0): ?>

        <?php while ($service = $result->fetch_assoc()): ?>

            <div class="service-card">

                <h3>
                    <?php echo htmlspecialchars($service["service_name"]); ?>
                </h3>

                <p>
                    <?php echo htmlspecialchars($service["description"]); ?>
                </p>

                <p>
                    <strong>Price:</strong>
                    ₹<?php echo htmlspecialchars($service["price"]); ?>
                </p>

                <br>

                <a href="book-service.php?service_id=<?php echo $service["service_id"]; ?>">
                    <button>Book Service</button>
                </a>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>No services are currently available.</p>

    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>