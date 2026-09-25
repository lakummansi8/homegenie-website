<?php

$pageTitle = "Reviews";
$adminPath = "../../";
$assetPath = "../../../";

require_once "../../../config/db.php";

$stmt = $conn->prepare(
    "SELECT
        r.review_id,
        r.rating,
        r.review_comment,
        r.created_at,
        u.full_name AS customer_name,
        sp.full_name AS provider_name,
        s.service_name
     FROM reviews r
     LEFT JOIN users u
        ON r.user_id = u.user_id
     LEFT JOIN service_providers sp
        ON r.provider_id = sp.provider_id
     LEFT JOIN bookings b
        ON r.booking_id = b.booking_id
     LEFT JOIN services s
        ON b.service_id = s.service_id
     ORDER BY r.review_id DESC"
);

if (!$stmt) {
    die("Database query error: " . $conn->error);
}

$stmt->execute();

$result = $stmt->get_result();

ob_start();
?>

<div class="container-fluid py-4">

    <div class="mb-4">

        <h2 class="fw-bold">Customer Reviews</h2>

        <p class="text-muted">
            View all reviews submitted by customers.
        </p>

    </div>

    <?php if ($result->num_rows > 0) { ?>

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

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

                             <?php $srno = 1;?>

                            <?php while ($review = $result->fetch_assoc()) { ?>

                                <tr>

                                    <td><?php print $srno; ?></td>

                                    <td>
                                        <?php echo htmlspecialchars($review["customer_name"] ?? "Unknown"); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($review["provider_name"] ?? "Unknown"); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($review["service_name"] ?? "Unknown"); ?>
                                    </td>

                                    <td>

                                        <span class="text-warning">

                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {

                                                if ($i <= $review["rating"]) {
                                                    echo "★";
                                                } else {
                                                    echo "☆";
                                                }

                                            }
                                            ?>

                                        </span>

                                        <small class="text-muted">
                                            (<?php echo $review["rating"]; ?>/5)
                                        </small>

                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($review["review_comment"]); ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo date(
                                            "d M Y",
                                            strtotime($review["created_at"])
                                        );
                                        ?>
                                    </td>

                                </tr>
                                 <?php $srno++; ?>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    <?php } else { ?>

        <div class="alert alert-info">
            No customer reviews have been submitted yet.
        </div>

    <?php } ?>

</div>

<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";
?>
