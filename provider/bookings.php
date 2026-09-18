<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$stmt = $conn->prepare(
    "SELECT
        b.booking_id,
        b.booking_date,
        b.booking_time,
        b.booking_status,
        u.full_name AS customer_name,
        u.phone AS customer_phone,
        s.service_name
     FROM bookings b
     LEFT JOIN users u
        ON b.user_id = u.user_id
     LEFT JOIN services s
        ON b.service_id = s.service_id
     WHERE b.provider_id = ?
     ORDER BY b.booking_date DESC, b.booking_time DESC"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();

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

        <?php if ($result->num_rows > 0): ?>

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

                    <?php while ($booking = $result->fetch_assoc()): ?>

                        <?php
                        $status = $booking["booking_status"];
                        ?>

                        <tr>

                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($booking["customer_name"]); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["customer_phone"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["service_name"]); ?>
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

                                <?php if ($status === "Pending"): ?>

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

                                <?php elseif ($status === "Confirmed"): ?>

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