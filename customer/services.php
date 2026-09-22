<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$sql = "SELECT 
            s.service_id,
            s.service_name,
            s.description,
            s.price,
            sp.full_name AS provider_name,
            sp.area,
            sp.city
        FROM services s
        INNER JOIN service_providers sp
            ON s.provider_id = sp.provider_id
        WHERE sp.account_status = 'active'
        ORDER BY s.service_id DESC";

$result = $conn->query($sql);

$pageTitle = "Services";

require_once "layout/customer-layout.php";

?>

<div class="welcome">

    <h1>Available Services</h1>

    <p>
        Browse services offered by HomeGenie service providers.
    </p>

</div>


<div class="service-list">

    <?php if ($result->num_rows > 0) { ?>

        <?php while ($service = $result->fetch_assoc()) { ?>

            <div class="service-item">

                <div class="service-details">

                    <h2>
                        <?php echo htmlspecialchars($service["service_name"]); ?>
                    </h2>

                    <p class="service-description">
                        <?php echo htmlspecialchars($service["description"]); ?>
                    </p>

                    <div class="service-info">

                        <span>
                            Provider:
                            <strong>
                                <?php echo htmlspecialchars($service["provider_name"]); ?>
                            </strong>
                        </span>

                        <span>
                            Area:
                            <strong>
                                <?php echo htmlspecialchars($service["area"]); ?>
                            </strong>
                        </span>

                        <span>
                            City:
                            <strong>
                                <?php echo htmlspecialchars($service["city"]); ?>
                            </strong>
                        </span>

                    </div>

                </div>


                <div class="service-price">

                    <span>Starting Price</span>

                    <strong>
                        ₹<?php echo number_format($service["price"], 2); ?>
                    </strong>

                    <a href="book-service.php?service_id=<?php echo $service["service_id"]; ?>">
                        Book Service
                    </a>

                </div>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty-state">

            <h2>No Services Available</h2>

            <p>
                There are currently no services available.
            </p>

        </div>

    <?php } ?>

</div>


</div>
</main>

</div>

</body>
</html>