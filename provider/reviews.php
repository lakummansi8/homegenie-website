<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

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
     ORDER BY r.created_at DESC"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();

$pageTitle = "Reviews";
$pageCss = "reviews.css";

require_once "layout/provider-layout.php";
?>

<div class="reviews-page">

    <div class="reviews-header">

        <div>
            <h2>Reviews</h2>
            <p>View reviews received from your customers.</p>
        </div>

    </div>

    <div class="reviews-section">

        <?php if ($result->num_rows > 0): ?>

            <div class="reviews-list">

                <?php while ($review = $result->fetch_assoc()): ?>

                    <div class="review-card">

                        <div class="review-top">

                            <div class="customer-info">

                                <strong>
                                    <?php echo htmlspecialchars($review["customer_name"] ?? "Unknown Customer"); ?>
                                </strong>

                                <span>
                                    <?php echo htmlspecialchars($review["service_name"] ?? "Unknown Service"); ?>
                                </span>

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

                        <div class="review-rating">

                            <?php
                            $rating = (int)$review["rating"];

                            for ($i = 1; $i <= 5; $i++):

                                if ($i <= $rating):
                            ?>

                                    <span class="star filled">★</span>

                                <?php else: ?>

                                    <span class="star">★</span>

                                <?php
                                endif;

                            endfor;
                            ?>

                            <span class="rating-number">
                                <?php echo $rating; ?>/5
                            </span>

                        </div>

                        <div class="review-comment">

                            <?php if (!empty($review["review_comment"])): ?>

                                <p>
                                    <?php echo htmlspecialchars($review["review_comment"]); ?>
                                </p>

                            <?php else: ?>

                                <p class="no-comment">
                                    No comment provided.
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="no-reviews">

                <h3>No Reviews Found</h3>

                <p>You have not received any customer reviews yet.</p>

            </div>

        <?php endif; ?>

    </div>

</div>

</section>
</main>
</div>
</body>
</html>