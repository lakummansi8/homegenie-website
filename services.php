<?php

require_once "config/db.php";

$pageTitle = "Services";
$pageCss = "services.css";


/* Get selected category */

$categoryId = 0;

if (isset($_GET["category_id"])) {
    $categoryId = (int)$_GET["category_id"];
}


/* Get Categories */

$categoriesQuery = "SELECT * FROM categories WHERE category_status = 'Active' ORDER BY category_name ASC";

$categoriesResult = mysqli_query($conn, $categoriesQuery);


/* Get Services */

if ($categoryId > 0) {

    $servicesQuery = "SELECT * FROM services 
                      WHERE service_status = 'Active'
                      AND category_id = $categoryId
                      ORDER BY service_id DESC";

} else {

    $servicesQuery = "SELECT * FROM services 
                      WHERE service_status = 'Active'
                      ORDER BY service_id DESC";
}

$servicesResult = mysqli_query($conn, $servicesQuery);

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

    <section class="services-list-section" id="services">

        <div class="services-container">


            <!-- Heading -->

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
                    href="services.php#services"
                    class="category-button <?php echo $categoryId == 0 ? 'active' : ''; ?>"
                >
                    All Services
                </a>


                <?php if (mysqli_num_rows($categoriesResult) > 0) { ?>

                    <?php while ($category = mysqli_fetch_assoc($categoriesResult)) { ?>

                        <a
                            href="services.php?category_id=<?php echo $category["category_id"]; ?>#services"
                            class="category-button <?php echo $categoryId == $category["category_id"] ? 'active' : ''; ?>"
                        >
                            <?php echo htmlspecialchars($category["category_name"]); ?>
                        </a>

                    <?php } ?>

                <?php } ?>

            </div>


            <!-- Service Cards -->

            <div class="services-grid">


                <?php if (mysqli_num_rows($servicesResult) > 0) { ?>


                    <?php while ($service = mysqli_fetch_assoc($servicesResult)) { ?>


                        <?php

                        /* Get Category Name */

                        $categoryQuery = "SELECT category_name 
                                          FROM categories 
                                          WHERE category_id = " . $service["category_id"];

                        $categoryResult = mysqli_query($conn, $categoryQuery);

                        $category = mysqli_fetch_assoc($categoryResult);


                        /* Get Provider Name */

                        $providerQuery = "SELECT full_name 
                                          FROM service_providers 
                                          WHERE provider_id = " . $service["provider_id"];

                        $providerResult = mysqli_query($conn, $providerQuery);

                        $provider = mysqli_fetch_assoc($providerResult);

                        ?>


                        <div class="service-card">


                            <!-- Service Image -->

                            <div class="service-image">

                                <?php if (!empty($service["service_image"])) { ?>

                                    <img
                                        src="/homegenie-website/assets/services/<?php echo htmlspecialchars($service["service_image"]); ?>"
                                        alt="<?php echo htmlspecialchars($service["service_name"]); ?>"
                                    >

                                <?php } else { ?>

                                    <div class="image-placeholder">
                                        No Image Available
                                    </div>

                                <?php } ?>

                            </div>


                            <!-- Service Content -->

                            <div class="service-content">


                                <span class="service-category">

                                    <?php

                                    if ($category) {
                                        echo htmlspecialchars($category["category_name"]);
                                    } else {
                                        echo "Home Service";
                                    }

                                    ?>

                                </span>


                                <h3>
                                    <?php echo htmlspecialchars($service["service_name"]); ?>
                                </h3>


                                <p class="service-description">
                                    <?php echo htmlspecialchars($service["description"]); ?>
                                </p>


                                <!-- Provider -->

                                <div class="provider-info">

                                    <span>
                                        Service Provider
                                    </span>

                                    <strong>

                                        <?php

                                        if ($provider) {
                                            echo htmlspecialchars($provider["full_name"]);
                                        } else {
                                            echo "Service Provider";
                                        }

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
                                            ₹<?php echo number_format($service["price"], 2); ?>
                                        </strong>

                                    </div>


                                    <a
                                        href="customer/book-service.php?service_id=<?php echo $service["service_id"]; ?>&provider_id=<?php echo $service["provider_id"]; ?>"
                                        class="book-button"
                                    >
                                        Book Service
                                    </a>


                                </div>


                            </div>


                        </div>


                    <?php } ?>


                <?php } else { ?>


                    <div class="no-services">

                        <h3>
                            No Services Available
                        </h3>

                        <p>
                            There are currently no active services
                            in this category.
                        </p>

                        <a
                            href="services.php#services"
                            class="view-all-button"
                        >
                            View All Services
                        </a>

                    </div>


                <?php } ?>


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
                href="register.php"
                class="cta-button"
            >
                Get Started
            </a>

        </div>

    </section>


</main>


<?php include "includes/footer.php"; ?>