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

$stmt = $conn->prepare(
    "SELECT
        r.review_id,
        r.rating,
        r.review_comment,
        r.created_at,
        u.full_name AS customer_name,
        s.service_name
     FROM reviews r
     LEFT JOIN users u
        ON r.user_id = u.user_id
     LEFT JOIN bookings b
        ON r.booking_id = b.booking_id
     LEFT JOIN services s
        ON b.service_id = s.service_id
     WHERE r.provider_id = ?
     ORDER BY r.review_id DESC"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();

require_once "layout/provider-layout.php";
?>

<div class="reviews-container">

    <?php if ($result->num_rows > 0): ?>

        <div class="reviews-grid">

            <?php while ($review = $result->fetch_assoc()): ?>

                <div class="review-card">

                    <div class="review-header">

                        <div>
                            <h3>
                                <?php echo htmlspecialchars($review["customer_name"]); ?>
                            </h3>

                            <p>
                                <?php echo htmlspecialchars($review["service_name"]); ?>
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
```
