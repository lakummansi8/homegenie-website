<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$userId = $_SESSION["user_id"];

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

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

$pageTitle = "My Bookings";

require_once "layout/customer-layout.php";

?>

<div class="welcome">

    <h1>My Bookings</h1>

    <p>
        View and manage your service bookings.
    </p>

</div>


<?php if (isset($_GET["booking"]) && $_GET["booking"] === "success"): ?>

    <div class="success-message">
        Booking created successfully!
    </div>

<?php endif; ?>


<?php if ($result->num_rows > 0): ?>

    <div class="booking-list">

        <?php while ($booking = $result->fetch_assoc()): ?>

            <div class="booking-item">

                <div class="booking-header">

                    <div>

                        <h2>
                            <?php echo htmlspecialchars($booking["service_name"]); ?>
                        </h2>

                        <p>
                            Provider:
                            <strong>
                                <?php echo htmlspecialchars($booking["provider_name"]); ?>
                            </strong>
                        </p>

                    </div>


                    <div class="booking-status">

                        <span>Status</span>

                        <strong>
                            <?php echo htmlspecialchars($booking["booking_status"]); ?>
                        </strong>

                    </div>

                </div>


                <div class="booking-details">

                    <div>
                        <span>Price</span>
                        <p>
                            ₹<?php echo number_format($booking["price"], 2); ?>
                        </p>
                    </div>


                    <div>
                        <span>Date</span>
                        <p>
                            <?php echo htmlspecialchars($booking["booking_date"]); ?>
                        </p>
                    </div>


                    <div>
                        <span>Time</span>
                        <p>
                            <?php echo htmlspecialchars($booking["booking_time"]); ?>
                        </p>
                    </div>


                    <div>
                        <span>Address</span>
                        <p>
                            <?php echo htmlspecialchars($booking["booking_address"]); ?>
                        </p>
                    </div>

                </div>


                <?php if ($booking["booking_status"] === "Pending"): ?>

                    <div class="booking-actions">

                        <a href="cancel-booking.php?booking_id=<?php echo $booking["booking_id"]; ?>">
                            Cancel Booking
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        <?php endwhile; ?>

    </div>


<?php else: ?>

    <div class="empty-state">

        <h2>No Bookings Yet</h2>

        <p>
            You have not made any bookings yet.
        </p>

        <a href="services.php">
            Browse Services
        </a>

    </div>

<?php endif; ?>


</div>
</main>

</div>

</body>
</html>