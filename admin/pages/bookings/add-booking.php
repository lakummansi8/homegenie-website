<?php
$pageTitle = "Add Booking";
$assetPath = "../../../";
$adminPath = "../../";
require_once __DIR__ . "/../../../config/db.php";

$userId = "";
$providerId = "";
$serviceId = "";
$bookingDate = "";
$bookingTime = "";
$bookingAddress = "";
$bookingStatus = "pending";
$errors = [];

$customers = [];
$customerQuery = "SELECT user_id, full_name, email FROM users WHERE account_status = 'active' ORDER BY full_name ASC";
$customerResult = $conn->query($customerQuery);
if ($customerResult) {
    while ($row = $customerResult->fetch_assoc()) {
        $customers[] = $row;
    }
}

$providers = [];
$providerQuery = "SELECT provider_id, full_name, category_id FROM service_providers WHERE account_status = 'active' ORDER BY full_name ASC";
$providerResult = $conn->query($providerQuery);
if ($providerResult) {
    while ($row = $providerResult->fetch_assoc()) {
        $providers[] = $row;
    }
}

$services = [];
$serviceQuery = "SELECT service_id, service_name, provider_id, category_id, price FROM services WHERE service_status = 'active' ORDER BY service_name ASC";
$serviceResult = $conn->query($serviceQuery);
if ($serviceResult) {
    while ($row = $serviceResult->fetch_assoc()) {
        $services[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = (int)($_POST["user_id"] ?? 0);
    $providerId = (int)($_POST["provider_id"] ?? 0);
    $serviceId = (int)($_POST["service_id"] ?? 0);
    $bookingDate = trim($_POST["booking_date"] ?? "");
    $bookingTime = trim($_POST["booking_time"] ?? "");
    $bookingAddress = trim($_POST["booking_address"] ?? "");
    $bookingStatus = strtolower(trim($_POST["booking_status"] ?? "pending"));

    if ($userId <= 0) $errors[] = "Please select a customer.";
    if ($providerId <= 0) $errors[] = "Please select a service provider.";
    if ($serviceId <= 0) $errors[] = "Please select a service.";
    if ($bookingDate === "") $errors[] = "Please select a booking date.";
    if ($bookingTime === "") $errors[] = "Please select a booking time.";
    if ($bookingAddress === "") $errors[] = "Please enter the booking address.";
    
    $allowedStatuses = ["pending", "confirmed", "completed", "cancelled"];
    if (!in_array($bookingStatus, $allowedStatuses, true)) $errors[] = "Invalid booking status.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ? AND account_status = 'active' LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) $errors[] = "Selected customer was not found.";
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT provider_id FROM service_providers WHERE provider_id = ? AND account_status = 'active' LIMIT 1");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) $errors[] = "Selected service provider was not found.";
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT service_id FROM services WHERE service_id = ? AND provider_id = ? AND service_status = 'active' LIMIT 1");
        $stmt->bind_param("ii", $serviceId, $providerId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) $errors[] = "The selected service does not belong to the selected provider.";
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, provider_id, service_id, booking_date, booking_time, booking_address, booking_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissss", $userId, $providerId, $serviceId, $bookingDate, $bookingTime, $bookingAddress, $bookingStatus);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: bookings.php?success=booking_added");
            exit;
        }
        $errors[] = "Unable to create booking. Please try again.";
        $stmt->close();
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Add Booking</h3>
        <p class="text-muted">Create a new customer service booking.</p>
    </div>
    <div class="header-actions">
        <a href="bookings.php" class="btn btn-secondary">&larr; Back to Bookings</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Booking Details</strong>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer *</label>
                    <select class="form-select" name="user_id" required>
                        <option value="">Select Customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= (int)$customer["user_id"] ?>" <?= $userId == $customer["user_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($customer["full_name"]) ?> - <?= htmlspecialchars($customer["email"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Provider *</label>
                    <select class="form-select" name="provider_id" id="provider_id" required>
                        <option value="">Select Provider</option>
                        <?php foreach ($providers as $provider): ?>
                            <option value="<?= (int)$provider["provider_id"] ?>" <?= $providerId == $provider["provider_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($provider["full_name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service *</label>
                    <select class="form-select" name="service_id" id="service_id" required>
                        <option value="">Select Service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= (int)$service["service_id"] ?>" data-provider="<?= (int)$service["provider_id"] ?>" <?= $serviceId == $service["service_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($service["service_name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Status *</label>
                    <select class="form-select" name="booking_status" required>
                        <option value="pending" <?= $bookingStatus === "pending" ? "selected" : "" ?>>Pending</option>
                        <option value="confirmed" <?= $bookingStatus === "confirmed" ? "selected" : "" ?>>Confirmed</option>
                        <option value="completed" <?= $bookingStatus === "completed" ? "selected" : "" ?>>Completed</option>
                        <option value="cancelled" <?= $bookingStatus === "cancelled" ? "selected" : "" ?>>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Date *</label>
                    <input type="date" class="form-control" name="booking_date" value="<?= htmlspecialchars($bookingDate) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Time *</label>
                    <input type="time" class="form-control" name="booking_time" value="<?= htmlspecialchars($bookingTime) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Address *</label>
                <textarea class="form-control" name="booking_address" rows="4" required><?= htmlspecialchars($bookingAddress) ?></textarea>
            </div>

            <hr>
            <div class="admin-form-actions">
                <a href="bookings.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const providerSelect = document.getElementById("provider_id");
    const serviceSelect = document.getElementById("service_id");

    function filterServices() {
        const selectedProvider = providerSelect.value;
        const options = serviceSelect.querySelectorAll("option[data-provider]");

        options.forEach(function (option) {
            if (selectedProvider === "" || option.dataset.provider === selectedProvider) {
                option.hidden = false;
            } else {
                option.hidden = true;
            }
        });

        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.provider && selectedOption.dataset.provider !== selectedProvider) {
            serviceSelect.value = "";
        }
    }

    providerSelect.addEventListener("change", filterServices);
    filterServices();
});
</script>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
