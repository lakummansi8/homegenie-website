<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$serviceId = $_GET["service_id"] ?? "";

if ($serviceId === "") {
    header("Location: services.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT service_id, provider_id, service_name, price
     FROM services
     WHERE service_id = ? AND service_status = 'Active'"
);

$stmt->bind_param("i", $serviceId);
$stmt->execute();

$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    header("Location: services.php");
    exit;
}

$userName = $_SESSION["user_name"] ?? "Customer";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Service - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>

<body>

<div class="customer-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">
            <h1>HomeGenie</h1>
            <p>Customer Panel</p>
        </div>

        <nav class="sidebar-nav">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="services.php" class="active">
                Services
            </a>

            <a href="my-bookings.php">
                My Bookings
            </a>

            <a href="profile.php">
                My Profile
            </a>

        </nav>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </aside>


    <main class="main-area">

        <header class="top-header">

            <h2>Book Service</h2>

            <div class="header-user">
                <strong><?php echo htmlspecialchars($userName); ?></strong>
                <span>Customer</span>
            </div>

        </header>


        <div class="dashboard">

            <div class="welcome">

                <h1>Book a Service</h1>

                <p>
                    Enter your booking details to request this service.
                </p>

            </div>


            <div class="booking-form-section">

                <div class="selected-service">

                    <span>Selected Service</span>

                    <h2>
                        <?php echo htmlspecialchars($service["service_name"]); ?>
                    </h2>

                    <p>
                        Price:
                        <strong>
                            ₹<?php echo number_format($service["price"], 2); ?>
                        </strong>
                    </p>

                </div>


                <form action="booking-process.php" method="POST">

                    <input
                        type="hidden"
                        name="service_id"
                        value="<?php echo $service["service_id"]; ?>"
                    >

                    <input
                        type="hidden"
                        name="provider_id"
                        value="<?php echo $service["provider_id"]; ?>"
                    >


                    <div class="form-field">

                        <label>Booking Date</label>

                        <input
                            type="date"
                            name="booking_date"
                            required
                        >

                    </div>


                    <div class="form-field">

                        <label>Booking Time</label>

                        <input
                            type="time"
                            name="booking_time"
                            required
                        >

                    </div>


                    <div class="form-field">

                        <label>Booking Address</label>

                        <textarea
                            name="booking_address"
                            required
                            placeholder="Enter the address where the service is required"
                        ></textarea>

                    </div>


                    <div class="form-actions">

                        <a href="services.php">
                            Back to Services
                        </a>

                        <button type="submit">
                            Confirm Booking
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

</body>
</html>