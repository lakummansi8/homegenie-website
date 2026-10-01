```php
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

/* Get all bookings of this provider */
$sql = "SELECT * FROM bookings
        WHERE provider_id = $providerId
        ORDER BY booking_date DESC";

$result = mysqli_query($conn, $sql);

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

        <?php if (mysqli_num_rows($result) > 0): ?>

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

                    <?php

                    $customers = array();

                    while ($booking = mysqli_fetch_assoc($result)) {

                        $userId = $booking["user_id"];

                        /* Check if customer is already added */
                        if (!isset($customers[$userId])) {

                            $userSql = "SELECT full_name, email, phone
                                        FROM users
                                        WHERE user_id = $userId";

                            $userResult = mysqli_query($conn, $userSql);
                            $user = mysqli_fetch_assoc($userResult);

                            $customers[$userId] = array(
                                "full_name" => $user["full_name"],
                                "email" => $user["email"],
                                "phone" => $user["phone"],
                                "total_bookings" => 1,
                                "last_booking" => $booking["booking_date"]
                            );

                        } else {

                            $customers[$userId]["total_bookings"]++;

                        }
                    }

                    foreach ($customers as $customer):
                    ?>

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
                                <?php echo $customer["total_bookings"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($customer["last_booking"]); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

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

