<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$stmt = $conn->prepare(
    "SELECT
        u.user_id,
        u.full_name,
        u.email,
        u.phone,
        COUNT(b.booking_id) AS total_bookings,
        MAX(b.booking_date) AS last_booking_date
     FROM users u
     INNER JOIN bookings b
        ON u.user_id = b.user_id
     WHERE b.provider_id = ?
     GROUP BY
        u.user_id,
        u.full_name,
        u.email,
        u.phone
     ORDER BY last_booking_date DESC"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();

$pageTitle = "Customers";
$pageCss = "customers.css";

require_once "layout/provider-layout.php";
?>

<div class="customers-page">

    <div class="customers-header">
        <div>
            <h2>Customers</h2>
            <p>View customers who have booked your services.</p>
        </div>
    </div>

    <div class="customers-section">

        <?php if ($result->num_rows > 0): ?>

            <div class="customers-table-wrapper">

                <table class="customers-table">

                    <thead>

                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Bookings</th>
                            <th>Last Booking</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($customer = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($customer["full_name"]); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($customer["email"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($customer["phone"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($customer["total_bookings"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($customer["last_booking_date"]); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="no-customers">

                <h3>No Customers Found</h3>

                <p>No customers have booked your services yet.</p>

            </div>

        <?php endif; ?>

    </div>

</div>

</section>
</main>
</div>
</body>
</html>