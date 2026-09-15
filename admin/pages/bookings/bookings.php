<?php
$pageTitle = "Bookings";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$bookings = [];
$sql = "
    SELECT b.booking_id, b.user_id, b.provider_id, b.service_id, b.booking_date, b.booking_time, b.booking_address, b.booking_status, b.created_at, u.full_name AS customer_name, sp.full_name AS provider_name, s.service_name
    FROM bookings AS b
    LEFT JOIN users AS u ON b.user_id = u.user_id
    LEFT JOIN service_providers AS sp ON b.provider_id = sp.provider_id
    LEFT JOIN services AS s ON b.service_id = s.service_id
    ORDER BY b.booking_id DESC
";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Bookings</h3>
        <p class="text-muted">Manage customer bookings, service providers, appointments and booking status.</p>
    </div>
    <div class="header-actions">
        <a href="add-booking.php" class="btn btn-primary">+ Add Booking</a>
    </div>
</div>

<?php if (isset($_GET["success"])): ?>
    <div class="alert alert-success">
        <?php 
            if ($_GET["success"] === "booking_added") echo "Booking added successfully.";
            elseif ($_GET["success"] === "booking_updated") echo "Booking updated successfully.";
            elseif ($_GET["success"] === "booking_deleted") echo "Booking deleted successfully.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET["error"])): ?>
    <div class="alert alert-danger">
        <?php
            if ($_GET["error"] === "invalid_booking") echo "Invalid booking ID.";
            elseif ($_GET["error"] === "booking_not_found") echo "Booking not found.";
            elseif ($_GET["error"] === "delete_failed") echo "Unable to delete booking. Please try again.";
            else echo "Something went wrong.";
        ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white admin-card-header">
        <strong>All Bookings</strong>
        <span class="badge bg-light text-dark"><?= count($bookings) ?> Bookings</span>
    </div>
    <div class="card-body">
        <?php if (empty($bookings)): ?>
            <p class="text-muted">There are currently no bookings available.</p>
            <a href="add-booking.php" class="btn btn-primary">Add Booking</a>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Provider</th>
                            <th>Date & Time</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <?php
                                $bookingId = (int)$booking["booking_id"];
                                $customerName = !empty($booking["customer_name"]) ? $booking["customer_name"] : "Customer #" . $booking["user_id"];
                                $providerName = !empty($booking["provider_name"]) ? $booking["provider_name"] : "Provider #" . $booking["provider_id"];
                                $serviceName = !empty($booking["service_name"]) ? $booking["service_name"] : "Service #" . $booking["service_id"];
                                
                                $formattedDate = !empty($booking["booking_date"]) ? date("d M Y", strtotime($booking["booking_date"])) : "—";
                                $formattedTime = !empty($booking["booking_time"]) ? date("h:i A", strtotime($booking["booking_time"])) : "—";
                                $formattedCreated = !empty($booking["created_at"]) ? date("d M Y", strtotime($booking["created_at"])) : "—";
                                $status = trim($booking["booking_status"] ?? "Unknown");
                            ?>
                            <tr>
                                <td>#<?= $bookingId ?></td>
                                <td><strong><?= htmlspecialchars($customerName) ?></strong></td>
                                <td><?= htmlspecialchars($serviceName) ?></td>
                                <td><?= htmlspecialchars($providerName) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($formattedDate) ?></strong><br>
                                    <?= htmlspecialchars($formattedTime) ?>
                                </td>
                                <td><?= nl2br(htmlspecialchars($booking["booking_address"] ?? "")) ?></td>
                                <td>
                                    <?php if ($status === "Pending"): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($status === "Confirmed"): ?>
                                        <span class="badge bg-primary">Confirmed</span>
                                    <?php elseif ($status === "Completed"): ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php elseif ($status === "Cancelled"): ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($status) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($formattedCreated) ?></td>
                                <td>
                                    <a href="edit-booking.php?id=<?= $bookingId ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete-booking.php?id=<?= $bookingId ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
