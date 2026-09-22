<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$userId = $_SESSION["user_id"];


if (isset($_POST["submit_review"])) {

    $bookingId = $_POST["booking_id"];
    $providerId = $_POST["provider_id"];
    $rating = $_POST["rating"];
    $comment = $_POST["review_comment"];

    $query = "INSERT INTO reviews
              (booking_id, user_id, provider_id, rating, review_comment)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    $stmt->bind_param(
        "iiiis",
        $bookingId,
        $userId,
        $providerId,
        $rating,
        $comment
    );

    $stmt->execute();
}


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
     WHERE b.user_id = ?
     AND b.booking_status = 'Completed'"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();


$pageTitle = "Reviews";

require_once "layout/customer-layout.php";

?>


<div class="information">

    <h2>Reviews</h2>

    <p>
        Give your review for completed services.
    </p>


    <?php if ($result->num_rows > 0) { ?>


        <?php while ($booking = $result->fetch_assoc()) { ?>


            <div class="information">

                <h3>
                    <?php echo htmlspecialchars($booking["service_name"]); ?>
                </h3>

                <p>
                    Provider:
                    <?php echo htmlspecialchars($booking["full_name"]); ?>
                </p>


                <form method="POST">

                    <input
                        type="hidden"
                        name="booking_id"
                        value="<?php echo $booking["booking_id"]; ?>"
                    >

                    <input
                        type="hidden"
                        name="provider_id"
                        value="<?php echo $booking["provider_id"]; ?>"
                    >


                    <label>Rating</label>

                    <br>

                    <select name="rating" required>

                        <option value="">
                            Select Rating
                        </option>

                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>

                    </select>


                    <br><br>


                    <label>Review</label>

                    <br>

                    <textarea
                        name="review_comment"
                        placeholder="Write your review"
                        required
                    ></textarea>


                    <br><br>


                    <button
                        type="submit"
                        name="submit_review"
                    >
                        Submit Review
                    </button>

                </form>

            </div>


        <?php } ?>


    <?php } else { ?>


        <h3>No Completed Bookings</h3>

        <p>
            You can review a service after it is completed.
        </p>


    <?php } ?>


</div>


</div>

</main>

</div>

</body>

</html>