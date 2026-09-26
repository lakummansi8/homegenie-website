<?php

$pageTitle = "Reviews";
$pageCss = "reviews.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$q = "select * from reviews order by review_id desc";
$res = mysqli_query($conn, $q);

if (!$res)
{
    die("Review query failed.");
}

ob_start();
?>

<div class="reviews-page">

    <div class="reviews-header">

        <div class="reviews-heading">

            <span class="reviews-eyebrow">
                Review Management
            </span>

            <h2>
                Customer Reviews
            </h2>

            <p>
                View reviews submitted by HomeGenie customers.
            </p>

        </div>

    </div>


    <div class="reviews-card">

        <div class="reviews-card-header">

            <div>

                <span class="reviews-card-eyebrow">
                    Customer Feedback
                </span>

                <h3>
                    All Reviews
                </h3>

            </div>

            <span class="reviews-count">
                <?php echo mysqli_num_rows($res); ?>
                Reviews
            </span>

        </div>


        <?php if (mysqli_num_rows($res) == 0) { ?>

            <div class="reviews-empty">

                <h4>
                    No Reviews Found
                </h4>

                <p>
                    There are currently no customer reviews in the system.
                </p>

            </div>

        <?php } else { ?>

            <div class="reviews-table-wrapper">

                <table class="reviews-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Provider</th>
                            <th>Service</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $srno = 1;

                        while ($review = mysqli_fetch_array($res))
                        {

                            /* Customer */

                            $userId = $review["user_id"];

                            $q1 = "select * from users where user_id = $userId";
                            $res1 = mysqli_query($conn, $q1);

                            $customerName = "Unknown";

                            if (mysqli_num_rows($res1) > 0)
                            {
                                $customer = mysqli_fetch_array($res1);
                                $customerName = $customer["full_name"];
                            }


                            /* Provider */

                            $providerId = $review["provider_id"];

                            $q2 = "select * from service_providers where provider_id = $providerId";
                            $res2 = mysqli_query($conn, $q2);

                            $providerName = "Unknown";

                            if (mysqli_num_rows($res2) > 0)
                            {
                                $provider = mysqli_fetch_array($res2);
                                $providerName = $provider["full_name"];
                            }


                            /* Booking */

                            $bookingId = $review["booking_id"];

                            $q3 = "select * from bookings where booking_id = $bookingId";
                            $res3 = mysqli_query($conn, $q3);

                            $serviceName = "Unknown";

                            if (mysqli_num_rows($res3) > 0)
                            {
                                $booking = mysqli_fetch_array($res3);

                                $serviceId = $booking["service_id"];

                                $q4 = "select * from services where service_id = $serviceId";
                                $res4 = mysqli_query($conn, $q4);

                                if (mysqli_num_rows($res4) > 0)
                                {
                                    $service = mysqli_fetch_array($res4);
                                    $serviceName = $service["service_name"];
                                }
                            }

                        ?>

                            <tr>

                                <td>

                                    <span class="review-id">
                                        <?php echo $srno; ?>
                                    </span>

                                </td>


                                <td>

                                    <strong class="review-customer">
                                        <?php
                                        echo htmlspecialchars($customerName);
                                        ?>
                                    </strong>

                                </td>


                                <td>

                                    <span class="review-provider">
                                        <?php
                                        echo htmlspecialchars($providerName);
                                        ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="review-service">
                                        <?php
                                        echo htmlspecialchars($serviceName);
                                        ?>
                                    </span>

                                </td>


                                <td>

                                    <div class="review-rating">

                                        <span class="review-stars">

                                            <?php

                                            for ($i = 1; $i <= 5; $i++)
                                            {
                                                if ($i <= $review["rating"])
                                                {
                                                    echo "★";
                                                }
                                                else
                                                {
                                                    echo "☆";
                                                }
                                            }

                                            ?>

                                        </span>

                                        <span class="review-rating-number">
                                            <?php
                                            echo $review["rating"];
                                            ?>/5
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <div class="review-comment">

                                        <?php

                                        if ($review["review_comment"] != "")
                                        {
                                            echo htmlspecialchars(
                                                $review["review_comment"]
                                            );
                                        }
                                        else
                                        {
                                            echo "No review comment";
                                        }

                                        ?>

                                    </div>

                                </td>


                                <td>

                                    <span class="review-date">

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $review["created_at"]
                                            )
                                        );

                                        ?>

                                    </span>

                                </td>

                            </tr>

                        <?php

                            $srno++;

                        }

                        ?>

                    </tbody>

                </table>

            </div>

        <?php } ?>

    </div>

</div>

<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>