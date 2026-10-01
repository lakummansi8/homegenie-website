```php
<?php

session_start();

if (
    !isset($_SESSION["provider_logged_in"]) ||
    $_SESSION["provider_logged_in"] !== true
) {
    header("Location: /homegenie-website/auth/login.php");
    exit;
}

require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$pageTitle = "Reviews";
$pageCss = "reviews.css";


/* Get reviews of this provider */

$sql = "SELECT * FROM reviews
        WHERE provider_id = $providerId
        ORDER BY review_id DESC";

$result = mysqli_query($conn, $sql);

require_once "layout/provider-layout.php";
?>

<div class="reviews-container">

    <?php if (mysqli_num_rows($result) > 0): ?>

        <div class="reviews-grid">

            <?php while ($review = mysqli_fetch_assoc($result)): ?>

                <?php

                /* Get customer name */

                $userId = $review["user_id"];

                $userSql = "SELECT full_name
                            FROM users
                            WHERE user_id = $userId";

                $userResult = mysqli_query($conn, $userSql);
                $user = mysqli_fetch_assoc($userResult);


                /* Get service ID from booking */

                $bookingId = $review["booking_id"];

                $bookingSql = "SELECT service_id
                               FROM bookings
                               WHERE booking_id = $bookingId";

                $bookingResult = mysqli_query($conn, $bookingSql);
                $booking = mysqli_fetch_assoc($bookingResult);


                /* Get service name */

                $serviceName = "Unknown Service";

                if ($booking) {

                    $serviceId = $booking["service_id"];

                    $serviceSql = "SELECT service_name
                                   FROM services
                                   WHERE service_id = $serviceId";

                    $serviceResult = mysqli_query($conn, $serviceSql);
                    $service = mysqli_fetch_assoc($serviceResult);

                    if ($service) {
                        $serviceName = $service["service_name"];
                    }
                }


                /* Customer name */

                if ($user) {
                    $customerName = $user["full_name"];
                } else {
                    $customerName = "Unknown Customer";
                }

                ?>

                <div class="review-card">

                    <div class="review-header">

                        <div>
                            <h3>
                                <?php echo htmlspecialchars($customerName); ?>
                            </h3>

                            <p>
                                <?php echo htmlspecialchars($serviceName); ?>
                            </p>
                        </div>

                        <div class="review-rating">

                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $review["rating"]) {
                                    echo "★";
                                } else {
                                    echo "☆";
                                }
                            }
                            ?>

                        </div>

                    </div>

                    <div class="review-comment">

                        <p>
                            <?php echo htmlspecialchars($review["review_comment"]); ?>
                        </p>

                    </div>

                    <div class="review-date">

                        <?php
                        echo date(
                            "d M Y",
                            strtotime($review["created_at"])
                        );
                        ?>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty-review">

            <h3>No Reviews Yet</h3>

            <p>
                You have not received any customer reviews yet.
            </p>

        </div>

    <?php endif; ?>

</div>

</main>
</div>

</body>
</html>
