```php
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

/* Get all bookings of this provider */
$sql = "SELECT * FROM bookings 
        WHERE provider_id = $providerId
        ORDER BY booking_date DESC, booking_time DESC";

$result = mysqli_query($conn, $sql);

$pageTitle = "Bookings";
$pageCss = "bookings.css";

require_once "layout/provider-layout.php";
?>

<div class="bookings-page">

    <div class="bookings-header">
        <div>
            <h2>Bookings</h2>
            <p>Manage bookings received from customers.</p>
        </div>
    </div>

    <?php if (isset($_GET["updated"])): ?>

        <div class="booking-message success-message">
            Booking status updated successfully.
        </div>

    <?php endif; ?>

    <div class="bookings-section">

        <?php if (mysqli_num_rows($result) > 0): ?>

            <div class="bookings-table-wrapper">

                <table class="bookings-table">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($booking = mysqli_fetch_assoc($result)): ?>

                        <?php

                        /* Get customer details */
                        $userId = $booking["user_id"];

                        $userSql = "SELECT full_name, phone 
                                    FROM users 
                                    WHERE user_id = $userId";

                        $userResult = mysqli_query($conn, $userSql);
                        $user = mysqli_fetch_assoc($userResult);

                        /* Get service details */
                        $serviceId = $booking["service_id"];

                        $serviceSql = "SELECT service_name 
                                       FROM services 
                                       WHERE service_id = $serviceId";

                        $serviceResult = mysqli_query($conn, $serviceSql);
                        $service = mysqli_fetch_assoc($serviceResult);

                        /* Booking status */
                        $status = $booking["booking_status"];

                        if ($status == "") {
                            $status = "Pending";
                        }

                        ?>

                        <tr>

                            <td>
                                <strong>
                                    <?php
                                    if ($user) {
                                        echo htmlspecialchars($user["full_name"]);
                                    } else {
                                        echo "Unknown";
                                    }
                                    ?>
                                </strong>
                            </td>

                            <td>
                                <?php
                                if ($user) {
                                    echo htmlspecialchars($user["phone"]);
                                } else {
                                    echo "Not Available";
                                }
                                ?>
                            </td>

                            <td>
                                <?php
                                if ($service) {
                                    echo htmlspecialchars($service["service_name"]);
                                } else {
                                    echo "Unknown Service";
                                }
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_date"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["booking_time"]); ?>
                            </td>

                            <td>

                                <span class="booking-status <?php echo strtolower($status); ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>

                            </td>

                            <td>

                                <?php if ($status == "Pending"): ?>

                                    <div class="booking-actions">

                                        <form method="POST" action="update-booking.php">

                                            <input
                                                type="hidden"
                                                name="booking_id"
                                                value="<?php echo $booking["booking_id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="accept"
                                            >

                                            <button
                                                type="submit"
                                                class="accept-button"
                                            >
                                                Accept
                                            </button>

                                        </form>

                                        <form method="POST" action="update-booking.php">

                                            <input
                                                type="hidden"
                                                name="booking_id"
                                                value="<?php echo $booking["booking_id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="reject"
                                            >

                                            <button
                                                type="submit"
                                                class="reject-button"
                                            >
                                                Reject
                                            </button>

                                        </form>

                                    </div>

                                <?php elseif ($status == "Accepted"): ?>

                                    <div class="booking-actions">

                                        <form method="POST" action="update-booking.php">

                                            <input
                                                type="hidden"
                                                name="booking_id"
                                                value="<?php echo $booking["booking_id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="complete"
                                            >

                                            <button
                                                type="submit"
                                                class="complete-button"
                                            >
                                                Mark as Completed
                                            </button>

                                        </form>

                                        <form method="POST" action="update-booking.php">

                                            <input
                                                type="hidden"
                                                name="booking_id"
                                                value="<?php echo $booking["booking_id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="cancel"
                                            >

                                            <button
                                                type="submit"
                                                class="cancel-button"
                                                onclick="return confirm('Are you sure you want to cancel this booking?');"
                                            >
                                                Cancel Booking
                                            </button>

                                        </form>

                                    </div>

                                <?php else: ?>

                                    <span class="no-action">
                                        No Action
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="no-bookings">

                <h3>No Bookings Found</h3>
                <p>You do not have any customer bookings yet.</p>

            </div>

        <?php endif; ?>

    </div>

</div>

</section>
</main>
</div>
</body>
</html>

