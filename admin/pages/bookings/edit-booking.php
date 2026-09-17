<?php
$pageTitle = "Edit Booking";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

$result = $conn->query("SELECT * FROM bookings WHERE booking_id = $id");
$booking = $result->fetch_assoc();

if (!$booking) {
    header("Location: bookings.php?error=booking_not_found");
    exit;
}

$customers = $conn->query("SELECT user_id, full_name, email FROM users WHERE account_status = 'active'");
$providers = $conn->query("SELECT provider_id, full_name FROM service_providers WHERE account_status = 'active'");
$services = $conn->query("SELECT service_id, service_name, provider_id FROM services WHERE service_status = 'active'");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $userId = (int)$_POST["user_id"];
    $providerId = (int)$_POST["provider_id"];
    $serviceId = (int)$_POST["service_id"];
    $bookingDate = $_POST["booking_date"];
    $bookingTime = $_POST["booking_time"];
    $bookingAddress = $_POST["booking_address"];
    $bookingStatus = $_POST["booking_status"];

    $stmt = $conn->prepare(
        "UPDATE bookings SET user_id=?, provider_id=?, service_id=?,
        booking_date=?, booking_time=?, booking_address=?, booking_status=?
        WHERE booking_id=?"
    );

    $stmt->bind_param(
        "iiissssi",
        $userId,
        $providerId,
        $serviceId,
        $bookingDate,
        $bookingTime,
        $bookingAddress,
        $bookingStatus,
        $id
    );

    if ($stmt->execute()) {
        header("Location: bookings.php?success=booking_updated");
        exit;
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Edit Booking</h3>
        <p class="text-muted">Update booking details.</p>
    </div>
    <div class="header-actions">
        <a href="bookings.php" class="btn btn-secondary">← Back to Bookings</a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Booking Details</strong>
    </div>

    <div class="card-body">
        <form method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer</label>
                    <select class="form-select" name="user_id" required>
                        <?php while ($customer = $customers->fetch_assoc()): ?>
                            <option value="<?= $customer["user_id"] ?>"
                                <?= $customer["user_id"] == $booking["user_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($customer["full_name"]) ?>
                                - <?= htmlspecialchars($customer["email"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Provider</label>
                    <select class="form-select" name="provider_id" required>
                        <?php while ($provider = $providers->fetch_assoc()): ?>
                            <option value="<?= $provider["provider_id"] ?>"
                                <?= $provider["provider_id"] == $booking["provider_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($provider["full_name"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Service</label>
                    <select class="form-select" name="service_id" required>
                        <?php while ($service = $services->fetch_assoc()): ?>
                            <option value="<?= $service["service_id"] ?>"
                                <?= $service["service_id"] == $booking["service_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($service["service_name"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Status</label>
                    <select class="form-select" name="booking_status" required>
                        <option value="Pending" <?= $booking["booking_status"] == "Pending" ? "selected" : "" ?>>Pending</option>
                        <option value="Accepted" <?= $booking["booking_status"] == "Accepted" ? "selected" : "" ?>>Accepted</option>
                        <option value="Completed" <?= $booking["booking_status"] == "Completed" ? "selected" : "" ?>>Completed</option>
                        <option value="Cancelled" <?= $booking["booking_status"] == "Cancelled" ? "selected" : "" ?>>Cancelled</option>
                    </select>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Date</label>
                    <input type="date" class="form-control"
                           name="booking_date"
                           value="<?= $booking["booking_date"] ?>"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Time</label>
                    <input type="time" class="form-control"
                           name="booking_time"
                           value="<?= $booking["booking_time"] ?>"
                           required>
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label">Booking Address</label>
                <textarea class="form-control"
                          name="booking_address"
                          rows="4"
                          required><?= htmlspecialchars($booking["booking_address"]) ?></textarea>
            </div>

            <hr>

            <a href="bookings.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Booking</button>

        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>