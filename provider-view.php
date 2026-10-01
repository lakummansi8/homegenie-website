<?php

require_once "config/db.php";

$pageTitle = "Service Providers";
$pageCss = "../pages/provider-view.css";

$providerResult = $conn->query(
    "SELECT * FROM service_providers
     WHERE account_status = 'Active'
     ORDER BY provider_id DESC"
);

?>

<?php include "includes/header.php"; ?>


<main class="provider-view-page">


    <!-- Provider Hero -->

    <section class="provider-hero">

        <div class="provider-hero-content">

            <span class="provider-hero-label">
                OUR PROFESSIONALS
            </span>

            <h1>
                Meet Our Service Providers
            </h1>

            <p>
                Choose a professional and book the service you need.
            </p>

        </div>

    </section>


    <!-- Providers Section -->

    <section class="providers-list-section">

        <div class="section-container">

            <div class="provider-grid">

                <?php if ($providerResult && $providerResult->num_rows > 0): ?>

                    <?php while ($provider = $providerResult->fetch_assoc()): ?>

                        <?php

                        $categoryResult = $conn->query(
                            "SELECT category_name FROM categories
                             WHERE category_id = " . $provider["category_id"]
                        );

                        $category = $categoryResult->fetch_assoc();


                        $serviceResult = $conn->query(
                            "SELECT * FROM services
                             WHERE provider_id = " . $provider["provider_id"] . "
                             AND service_status = 'Active'
                             LIMIT 1"
                        );

                        $service = $serviceResult->fetch_assoc();

                        ?>


                        <div class="provider-card">


                            <div class="provider-image">

                                <?php if (!empty($provider["profile_image"])): ?>

                                    <img
                                        src="/homegenie-website/assets/providers/<?php echo htmlspecialchars($provider["profile_image"]); ?>"
                                        alt="<?php echo htmlspecialchars($provider["full_name"]); ?>"
                                    >

                                <?php else: ?>

                                    <div class="provider-placeholder">

                                        <?php
                                        echo strtoupper(
                                            substr($provider["full_name"], 0, 1)
                                        );
                                        ?>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <div class="provider-card-content">

                                <h2>
                                    <?php echo htmlspecialchars($provider["full_name"]); ?>
                                </h2>


                                <span class="provider-category">

                                    <?php
                                    echo htmlspecialchars(
                                        $category["category_name"] ?? "Service Provider"
                                    );
                                    ?>

                                </span>


                                <?php if ($service): ?>

                                    <h3>
                                        <?php echo htmlspecialchars($service["service_name"]); ?>
                                    </h3>


                                    <p class="provider-price">

                                        Starting from

                                        <strong>
                                            ₹<?php echo number_format($service["price"], 2); ?>
                                        </strong>

                                    </p>

                                <?php else: ?>

                                    <p class="provider-price">
                                        Service information not available
                                    </p>

                                <?php endif; ?>


                                <p class="provider-experience">

                                    <?php echo htmlspecialchars($provider["experience"]); ?>

                                    years experience

                                </p>


                                <p class="provider-location">

                                    <?php echo htmlspecialchars($provider["area"]); ?>,

                                    <?php echo htmlspecialchars($provider["city"]); ?>

                                </p>


                                <?php if ($service): ?>

                                    <a
                                        href="customer/book-service.php?service_id=<?php echo $service["service_id"]; ?>"
                                        class="book-button"
                                    >
                                        Book Now
                                    </a>

                                <?php else: ?>

                                    <button
                                        class="book-button disabled-button"
                                        disabled
                                    >
                                        Not Available
                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>


                    <?php endwhile; ?>


                <?php else: ?>

                    <p class="no-data">
                        No service providers available at the moment.
                    </p>

                <?php endif; ?>


            </div>

        </div>

    </section>


</main>


<?php include "includes/footer.php"; ?>