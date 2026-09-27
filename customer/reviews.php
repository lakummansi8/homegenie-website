<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


$userId = $_SESSION["user_id"];


/* Submit Review */

if (isset($_POST["submit_review"]))
{
    $bookingId = $_POST["booking_id"];
    $providerId = $_POST["provider_id"];
    $rating = $_POST["rating"];
    $comment = $_POST["review_comment"];


    /* Check Empty Fields */

    if (
        $bookingId == "" ||
        $providerId == "" ||
        $rating == "" ||
        $comment == ""
    )
    {
        header("Location: reviews.php?error=invalid");
        exit;
    }


    /* Check Existing Review */

    $q = "select * from reviews
          where booking_id = $bookingId
          and user_id = $userId";

    $checkResult = mysqli_query($conn, $q);


    if (mysqli_num_rows($checkResult) > 0)
    {
        header("Location: reviews.php?error=already_reviewed");
        exit;
    }


    /* Save Review */

    $q2 = "insert into reviews
           (
               booking_id,
               user_id,
               provider_id,
               rating,
               review_comment
           )
           values
           (
               $bookingId,
               $userId,
               $providerId,
               $rating,
               '$comment'
           )";

    $result = mysqli_query($conn, $q2);


    if ($result)
    {
        header("Location: reviews.php?success=1");
        exit;
    }
    else
    {
        header("Location: reviews.php?error=failed");
        exit;
    }
}


/* Get Completed Bookings */

$q = "select * from bookings
      where user_id = $userId
      and booking_status = 'Completed'
      order by booking_id desc";

$result = mysqli_query($conn, $q);


$pageTitle = "Reviews";
$pageCss = "reviews.css";


ob_start();

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


    <?php

    if (isset($_GET["success"]))
    {
    ?>

        <div class="success-message">
            Your review has been submitted successfully.
        </div>

    <?php
    }


    if (isset($_GET["error"]))
    {
    ?>

        <div class="error-message">

            <?php

            if ($_GET["error"] == "already_reviewed")
            {
                echo "You have already reviewed this booking.";
            }
            elseif ($_GET["error"] == "invalid")
            {
                echo "Please provide a valid rating and review.";
            }
            else
            {
                echo "Unable to submit your review.";
            }

            ?>

        </div>

    <?php
    }


    if (mysqli_num_rows($result) > 0)
    {

        $hasReviewPending = false;

    ?>

        <div class="review-list">

            <?php

            while ($booking = mysqli_fetch_array($result))
            {

                $bookingId = $booking["booking_id"];
                $providerId = $booking["provider_id"];
                $serviceId = $booking["service_id"];


                /* Check Whether Booking Already Has Review */

                $q2 = "select * from reviews
                       where booking_id = $bookingId
                       and user_id = $userId";

                $reviewResult = mysqli_query($conn, $q2);


                if (mysqli_num_rows($reviewResult) > 0)
                {
                    continue;
                }


                $hasReviewPending = true;


                /* Get Service */

                $q3 = "select * from services
                       where service_id = $serviceId";

                $serviceResult = mysqli_query($conn, $q3);

                $service = mysqli_fetch_array($serviceResult);


                /* Get Provider */

                $q4 = "select * from service_providers
                       where provider_id = $providerId";

                $providerResult = mysqli_query($conn, $q4);

                $provider = mysqli_fetch_array($providerResult);

            ?>

                <div class="review-card">


                    <div class="review-card-header">

                        <div>

                            <h2>
                                <?php
                                echo htmlspecialchars(
                                    $service["service_name"]
                                );
                                ?>
                            </h2>

                            <p>

                                Provider:

                                <?php
                                echo htmlspecialchars(
                                    $provider["full_name"]
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
                            value="<?php echo $bookingId; ?>"
                        >


                        <input
                            type="hidden"
                            name="provider_id"
                            value="<?php echo $providerId; ?>"
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

            <?php
            }

            ?>

        </div>


        <?php

        if (!$hasReviewPending)
        {
        ?>

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

        <?php
        }

    }
    else
    {
    ?>

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

    <?php
    }

    ?>

</div>


<?php

$pageContent = ob_get_clean();

require_once "layout/customer-layout.php";

?>