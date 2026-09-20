<?php

require_once "config/db.php";

$pageTitle = "Services";
$pageCss = "services.css";


$categoryId = isset($_GET["category_id"])
    ? (int)$_GET["category_id"]
    : 0;


/* Get Categories */

$categoriesQuery = "
    SELECT
        category_id,
        category_name
    FROM categories
    WHERE category_status = 'Active'
    ORDER BY category_name ASC
";

$categoriesResult = $conn->query($categoriesQuery);


/* Get Services */

if ($categoryId > 0) {

    $servicesQuery = "
        SELECT
            s.service_id,
            s.category_id,
            s.provider_id,
            s.service_name,
            s.description,
            s.price,
            s.service_image,
            c.category_name,
            sp.full_name AS provider_name
        FROM services s
        LEFT JOIN categories c
            ON s.category_id = c.category_id
        LEFT JOIN service_providers sp
            ON s.provider_id = sp.provider_id
        WHERE s.service_status = 'Active'
        AND s.category_id = ?
        ORDER BY s.service_id DESC
    ";

    $stmt = $conn->prepare($servicesQuery);

    $stmt->bind_param("i", $categoryId);

    $stmt->execute();

    $servicesResult = $stmt->get_result();

} else {

    $servicesQuery = "
        SELECT
            s.service_id,
            s.category_id,
            s.provider_id,
            s.service_name,
            s.description,
            s.price,
            s.service_image,
            c.category_name,
            sp.full_name AS provider_name
        FROM services s
        LEFT JOIN categories c
            ON s.category_id = c.category_id
        LEFT JOIN service_providers sp
            ON s.provider_id = sp.provider_id
        WHERE s.service_status = 'Active'
        ORDER BY s.service_id DESC
    ";

    $servicesResult = $conn->query($servicesQuery);
}

?>


<?php include "includes/header.php"; ?>


<main class="services-page">


    <!-- Hero -->

    <section class="services-hero">

        <div class="services-hero-content">

            <span class="section-label">
                HOME SERVICES
            </span>

            <h1>
                Find the Right Service
                <span>for Your Home</span>
            </h1>

            <p>
                Browse our available home services and connect with
                professionals for your home service needs.
            </p>

        </div>

    </section>


    <!-- Services -->

    <section class="services-list-section">

        <div class="services-container">


            <div class="services-heading">

                <span class="section-label">
                    OUR SERVICES
                </span>

                <h2>
                    Explore Our Services
                </h2>

                <p>
                    Choose a category to find the service you need.
                </p>

            </div>


            <!-- Category Buttons -->

            <div class="category-filter">


                <a
                    href="services.php"
                    class="category-button <?php echo $categoryId === 0 ? 'active' : ''; ?>"
                >
                    All Services
                </a>


                <?php if ($categoriesResult && $categoriesResult->num_rows > 0): ?>

                    <?php while ($category = $categoriesResult->fetch_assoc()): ?>

                        <a
                            href="services.php?category_id=<?php echo (int)$category["category_id"]; ?>"
                            class="category-button <?php echo $categoryId === (int)$category["category_id"] ? 'active' : ''; ?>"
                        >
                            <?php
                            echo htmlspecialchars(
                                $category["category_name"]
                            );
                            ?>
                        </a>

                    <?php endwhile; ?>

                <?php endif; ?>


            </div>


            <!-- Service Cards -->

            <div class="services-grid">


                <?php if ($servicesResult && $servicesResult->num_rows > 0): ?>


                    <?php while ($service = $servicesResult->fetch_assoc()): ?>


                        <div class="service-card">


                            <!-- Service Image -->

                            <div class="service-image">

                                <?php if (!empty($service["service_image"])): ?>

                                    <img
                                        src="/homegenie-website/assets/services/<?php echo htmlspecialchars($service["service_image"]); ?>"
                                        alt="<?php echo htmlspecialchars($service["service_name"]); ?>"
                                    >

                                <?php else: ?>

                                    <div class="image-placeholder">
                                        No Image Available
                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- Service Content -->

                            <div class="service-content">


                                <span class="service-category">

                                    <?php
                                    echo htmlspecialchars(
                                        $service["category_name"] ??
                                        "Home Service"
                                    );
                                    ?>

                                </span>


                                <h3>

                                    <?php
                                    echo htmlspecialchars(
                                        $service["service_name"]
                                    );
                                    ?>

                                </h3>


                                <p class="service-description">

                                    <?php
                                    echo htmlspecialchars(
                                        $service["description"]
                                    );
                                    ?>

                                </p>


                                <!-- Provider -->

                                <div class="provider-info">

                                    <span>
                                        Service Provider
                                    </span>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $service["provider_name"] ??
                                            "Service Provider"
                                        );
                                        ?>

                                    </strong>

                                </div>


                                <!-- Price and Button -->

                                <div class="service-footer">


                                    <div class="service-price">

                                        <small>
                                            Starting from
                                        </small>

                                        <strong>
                                            ₹<?php
                                            echo number_format(
                                                (float)$service["price"],
                                                2
                                            );
                                            ?>
                                        </strong>

                                    </div>


                                    <a
                                        href="customer/book-service.php?service_id=<?php echo (int)$service["service_id"]; ?>&provider_id=<?php echo (int)$service["provider_id"]; ?>"
                                        class="book-button"
                                    >
                                        Book Service
                                    </a>


                                </div>


                            </div>


                        </div>


                    <?php endwhile; ?>


                <?php else: ?>


                    <div class="no-services">

                        <h3>
                            No Services Available
                        </h3>

                        <p>
                            There are currently no active services
                            in this category.
                        </p>

                        <a
                            href="services.php"
                            class="view-all-button"
                        >
                            View All Services
                        </a>

                    </div>


                <?php endif; ?>


            </div>


        </div>

    </section>


    <!-- Call To Action -->

    <section class="services-cta">

        <div class="services-cta-content">

            <span class="section-label">
                NEED A SERVICE?
            </span>

            <h2>
                Find a Professional for Your Home
            </h2>

            <p>
                Choose a service and connect with a professional
                through HomeGenie.
            </p>

            <a
                href="customer/register.php"
                class="cta-button"
            >
                Get Started
            </a>

        </div>

    </section>


</main>


<?php include "includes/footer.php"; ?>