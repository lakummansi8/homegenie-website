
<?php
$pageTitle = "Bookings";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$q = "select * from bookings";
$res = mysqli_query($conn,$q);

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
        <span class="badge bg-light text-dark">Bookings</span>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($res) == 0): ?>
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

                        <?php while($row = mysqli_fetch_array($res)) { ?>

                            <?php
                            $user = $row['user_id'];
                            $q1 = "select * from users where user_id = $user";
                            $res1 = mysqli_query($conn,$q1);
                            $userrow = mysqli_fetch_array($res1);

                            $provider = $row['provider_id'];
                            $q2 = "select * from service_providers where provider_id = $provider";
                            $res2 = mysqli_query($conn,$q2);
                            $providerrow = mysqli_fetch_array($res2);

                            $service = $row['service_id'];
                            $q3 = "select * from services where service_id = $service";
                            $res3 = mysqli_query($conn,$q3);
                            $servicerow = mysqli_fetch_array($res3);
                            ?>

                            <tr>
                                <td>#<?php print $row['booking_id']; ?></td>

                                <td>
                                    <strong>
                                        <?php print $userrow['full_name']; ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php print $servicerow['service_name']; ?>
                                </td>

                                <td>
                                    <?php print $providerrow['full_name']; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php print $row['booking_date']; ?>
                                    </strong><br>
                                    <?php print $row['booking_time']; ?>
                                </td>

                                <td>
                                    <?php print $row['booking_address']; ?>
                                </td>

                                <td>
                                    <?php if ($row['booking_status'] == "Pending"): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>

                                    <?php elseif ($row['booking_status'] == "Confirmed"): ?>
                                        <span class="badge bg-primary">Confirmed</span>

                                    <?php elseif ($row['booking_status'] == "Completed"): ?>
                                        <span class="badge bg-success">Completed</span>

                                    <?php elseif ($row['booking_status'] == "Cancelled"): ?>
                                        <span class="badge bg-danger">Cancelled</span>

                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <?php print $row['booking_status']; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php print $row['created_at']; ?>
                                </td>

                                <td>
                                    <a href="edit-booking.php?id=<?php print $row['booking_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                    <a href="delete-booking.php?id=<?php print $row['booking_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>

                        <?php } ?>

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
