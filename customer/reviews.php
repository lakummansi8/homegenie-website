```php
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$userId = $_SESSION["user_id"];


/* Submit Review */

if (isset($_POST["submit_review"])) {

    $bookingId = (int)($_POST["booking_id"] ?? 0);
    $providerId = (int)($_POST["provider_id"] ?? 0);
    $rating = (int)($_POST["rating"] ?? 0);
    $comment = trim($_POST["review_comment"] ?? "");


    if (
        $bookingId <= 0 ||
        $providerId <= 0 ||
        $rating < 1 ||
        $rating > 5 ||
        $comment === ""
    ) {

        header("Location: reviews.php?error=invalid");
        exit;
    }


    /* Check whether this booking already has a review */

    $checkStmt = $conn->prepare(
        "SELECT review_id
         FROM reviews
         WHERE booking_id = ?
         AND user_id = ?
         LIMIT 1"
    );

    $checkStmt->bind_param(
        "ii",
        $bookingId,
        $userId
    );

    $checkStmt->execute();

    $checkResult = $checkStmt->get_result();


    if ($checkResult->num_rows > 0) {

        header("Location: reviews.php?error=already_reviewed");
        exit;
    }


    /* Save Review */

    $stmt = $conn->prepare(
        "INSERT INTO reviews
        (
            booking_id,
            user_id,
            provider_id,
            rating,
            review_comment
        )
        VALUES (?, ?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "iiiis",
        $bookingId,
        $userId,
        $providerId,
        $rating,
        $comment
    );


    if ($stmt->execute()) {

        header("Location: reviews.php?success=1");
        exit;

    } else {

        header("Location: reviews.php?error=failed");
        exit;
    }
}


/* Get Completed Bookings That Have Not Been Reviewed */

$stmt = $conn->prepare(
    "SELECT
        b.booking_id,
        b.provider_id,
        s.service_name,
        sp.full_name
     FROM bookings b

     LEFT JOIN services s
        ON b.service_id = s.service_id

     LEFT JOIN service_providers sp
        ON b.provider_id = sp.provider_id

     LEFT JOIN reviews r
        ON b.booking_id = r.booking_id

     WHERE b.user_id = ?
     AND b.booking_status = 'Completed'
     AND r.review_id IS NULL

     ORDER BY b.booking_id DESC"
);


$stmt->bind_param(
    "i",
    $userId
);

$stmt->execute();

$result = $stmt->get_result();


$pageTitle = "Reviews";

require_once "layout/customer-layout.php";

?>


<div class="reviews-page">


    <div class="reviews-heading">

        <h1>
            Reviews
        </h1>

        <p>
            Share your experience with the services you have completed.
        </p>

    </div>


    <?php if (isset($_GET["success"])) { ?>

        <div class="success-message">

            Your review has been submitted successfully.

        </div>

    <?php } ?>


    <?php if (isset($_GET["error"])) { ?>

        <div class="success-message">

            <?php

            if ($_GET["error"] === "already_reviewed") {

                echo "You have already reviewed this booking.";

            }

            elseif ($_GET["error"] === "invalid") {

                echo "Please provide a valid rating and review.";

            }

            else {

                echo "Unable to submit your review.";

            }

            ?>

        </div>

    <?php } ?>


    <?php if ($result->num_rows > 0) { ?>


        <div class="review-list">


            <?php while ($booking = $result->fetch_assoc()) { ?>


                <div class="review-card">


                    <div class="review-card-header">


                        <div>

                            <h2>

                                <?php
                                echo htmlspecialchars(
                                    $booking["service_name"]
                                );
                                ?>

                            </h2>


                            <p>

                                Provider:

                                <?php
                                echo htmlspecialchars(
                                    $booking["full_name"]
                                );
                                ?>

                            </p>

                        </div>


                        <span class="completed-label">
                            Completed
                        </span>


                    </div>


                    <form method="POST">


                        <input
                            type="hidden"
                            name="booking_id"
                            value="<?php
                            echo $booking["booking_id"];
                            ?>"
                        >


                        <input
                            type="hidden"
                            name="provider_id"
                            value="<?php
                            echo $booking["provider_id"];
                            ?>"
                        >


                        <div class="review-field">


                            <label>
                                Rating
                            </label>


                            <select
                                name="rating"
                                required
                            >

                                <option value="">
                                    Select Rating
                                </option>

                                <option value="5">
                                    5 - Excellent
                                </option>

                                <option value="4">
                                    4 - Very Good
                                </option>

                                <option value="3">
                                    3 - Good
                                </option>

                                <option value="2">
                                    2 - Average
                                </option>

                                <option value="1">
                                    1 - Poor
                                </option>

                            </select>


                        </div>


                        <div class="review-field">


                            <label>
                                Write a Review
                            </label>


                            <textarea
                                name="review_comment"
                                placeholder="Tell us about your experience..."
                                required
                            ></textarea>


                        </div>


                        <button
                            type="submit"
                            name="submit_review"
                            class="review-button"
                        >
                            Submit Review
                        </button>


                    </form>


                </div>


            <?php } ?>


        </div>


    <?php } else { ?>


        <div class="review-empty">


            <h2>
                No Reviews Pending
            </h2>


            <p>
                You have no completed bookings waiting for a review.
            </p>


            <a href="my-bookings.php">
                View My Bookings
            </a>


        </div>


    <?php } ?>


</div>
```
