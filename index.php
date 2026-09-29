<?php

require_once "config/db.php";

$pageTitle = "HomeGenie";
$pageCss = "home.css";


// Get active services
$servicesResult = $conn->query(
    "SELECT * FROM services
     WHERE service_status = 'Active'
     ORDER BY service_id DESC
     LIMIT 6"
);


// Get active service providers
$providersResult = $conn->query(
    "SELECT * FROM service_providers
     WHERE account_status = 'Active'
     ORDER BY provider_id DESC
     LIMIT 3"
);


// Get latest reviews
$reviewsResult = $conn->query(
    "SELECT * FROM reviews
     ORDER BY created_at DESC
     LIMIT 3"
);

?>

<?php include "includes/header.php"; ?>


<main class="home-page">


    <!-- Hero Section -->

    <section class="hero-section">

        <div class="hero-content">

            <span class="hero-small-title">
                HOME SERVICES MADE SIMPLE
            </span>

            <h1>
                Trusted Home Services,
                <span>Right at Your Doorstep</span>
            </h1>

            <p>
                Find reliable professionals for cleaning, plumbing,
                electrical work, painting, carpentry and more.
            </p>

            <div class="hero-buttons">

                <a
                    href="services.php"
                    class="primary-button"
                >
                    Explore Services
                </a>

                <a
                    href="#about"
                    class="secondary-button"
                >
                    Learn More
                </a>

            </div>

        </div>

    </section>


    <!-- About Section -->

    <section
        class="about-section"
        id="about"
    >

        <div class="section-container">

            <div class="about-content">

                <span class="section-label">
                    ABOUT HOMEGENIE
                </span>

                <h2>
                    Making Home Services
                    <span>Simple and Reliable</span>
                </h2>

                <p>
                    HomeGenie is a home service marketplace that connects
                    customers with service professionals for their everyday
                    home needs.
                </p>

                <p>
                    Whether you need a plumber, electrician, cleaner,
                    painter, carpenter or appliance repair professional,
                    HomeGenie helps you find the right service in one place.
                </p>

                <a
                    href="about.php"
                    class="text-button"
                >
                    Learn More About Us →
                </a>

            </div>

            <div class="about-box">

                <div class="about-box-item">

                    <strong>
                        Easy
                    </strong>

                    <span>
                        Find services easily
                    </span>

                </div>

                <div class="about-box-item">

                    <strong>
                        Trusted
                    </strong>

                    <span>
                        Connect with professionals
                    </span>

                </div>

                <div class="about-box-item">

                    <strong>
                        Convenient
                    </strong>

                    <span>
                        Book services from one place
                    </span>

                </div>

            </div>

        </div>

    </section>


    <!-- Services Section -->

    <section class="services-section">

        <div class="section-container">

            <div class="section-heading">

                <div>

                    <span class="section-label">
                        OUR SERVICES
                    </span>

                    <h2>
                        Popular Home Services
                    </h2>

                </div>

                <a
                    href="services.php"
                    class="view-more"
                >
                    View All Services →
                </a>

            </div>


            <div class="service-grid">

                <?php if ($servicesResult && $servicesResult->num_rows > 0): ?>

                    <?php while ($service = $servicesResult->fetch_assoc()): ?>

                        <?php
                        $categoryResult = $conn->query(
                            "SELECT category_name FROM categories
                             WHERE category_id = " . $service["category_id"]
                        );

                        $category = $categoryResult->fetch_assoc();
                        ?>

                        <div class="service-card">

                            <div class="service-image">

                                <?php if (!empty($service["service_image"])): ?>

                                    <img
                                        src="/homegenie-website/assets/services/<?php echo htmlspecialchars($service["service_image"]); ?>"
                                        alt="<?php echo htmlspecialchars($service["service_name"]); ?>"
                                    >

                                <?php else: ?>

                                    <div class="image-placeholder">
                                        Home Service
                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="service-card-content">

                                <span class="service-category">
                                    <?php echo htmlspecialchars($category["category_name"] ?? "Home Service"); ?>
                                </span>

                                <h3>
                                    <?php echo htmlspecialchars($service["service_name"]); ?>
                                </h3>

                                <p>
                                    <?php echo htmlspecialchars($service["description"]); ?>
                                </p>

                                <div class="service-bottom">

                                    <strong>
                                        ₹<?php echo number_format($service["price"], 2); ?>
                                    </strong>

                                    <a href="services.php">
                                        View
                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p class="no-data">
                        No services available at the moment.
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <!-- Providers Section -->

    <section class="providers-section">

        <div class="section-container">

            <div class="section-heading">

                <div>

                    <span class="section-label">
                        OUR PROFESSIONALS
                    </span>

                    <h2>
                        Meet Our Service Providers
                    </h2>

                </div>

                <a
                    href="provider-view.php"
                    class="view-more"
                >
                    View All Providers →
                </a>

            </div>


            <div class="provider-grid">

                <?php if ($providersResult && $providersResult->num_rows > 0): ?>

                    <?php while ($provider = $providersResult->fetch_assoc()): ?>

                        <?php
                        $categoryResult = $conn->query(
                            "SELECT category_name FROM categories
                             WHERE category_id = " . $provider["category_id"]
                        );

                        $category = $categoryResult->fetch_assoc();
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
                                        <?php echo strtoupper(substr($provider["full_name"], 0, 1)); ?>
                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="provider-card-content">

                                <h3>
                                    <?php echo htmlspecialchars($provider["full_name"]); ?>
                                </h3>

                                <span class="provider-category">
                                    <?php echo htmlspecialchars($category["category_name"] ?? "Service Provider"); ?>
                                </span>

                                <p>
                                    <?php echo htmlspecialchars($provider["experience"]); ?>
                                    years experience
                                </p>

                                <p class="provider-location">

                                    <?php echo htmlspecialchars($provider["area"]); ?>,
                                    
                                    <?php echo htmlspecialchars($provider["city"]); ?>

                                </p>

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


    <!-- Why Choose Us -->

    <section class="why-section">

        <div class="section-container">

            <div class="section-heading centered">

                <span class="section-label">
                    WHY HOMEGENIE
                </span>

                <h2>
                    Why Choose HomeGenie?
                </h2>

                <p>
                    Everything you need to find and book home services
                    conveniently.
                </p>

            </div>


            <div class="why-grid">

                <div class="why-card">

                    <div class="why-number">
                        01
                    </div>

                    <h3>
                        Wide Range of Services
                    </h3>

                    <p>
                        Find different home services in one convenient
                        marketplace.
                    </p>

                </div>


                <div class="why-card">

                    <div class="why-number">
                        02
                    </div>

                    <h3>
                        Professional Providers
                    </h3>

                    <p>
                        Connect with service providers who offer
                        professional home services.
                    </p>

                </div>


                <div class="why-card">

                    <div class="why-number">
                        03
                    </div>

                    <h3>
                        Easy Booking
                    </h3>

                    <p>
                        Choose a service and book it according to
                        your requirements.
                    </p>

                </div>


                <div class="why-card">

                    <div class="why-number">
                        04
                    </div>

                    <h3>
                        Convenient Experience
                    </h3>

                    <p>
                        Manage your home service bookings from one place.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- Testimonials -->

    <section class="testimonials-section">

        <div class="section-container">

            <div class="section-heading centered">

                <span class="section-label">
                    CUSTOMER REVIEWS
                </span>

                <h2>
                    What Our Customers Say
                </h2>

            </div>


            <div class="testimonial-grid">

                <?php if ($reviewsResult && $reviewsResult->num_rows > 0): ?>

                    <?php while ($review = $reviewsResult->fetch_assoc()): ?>

                        <?php
                        $userResult = $conn->query(
                            "SELECT full_name FROM users
                             WHERE user_id = " . $review["user_id"]
                        );

                        $user = $userResult->fetch_assoc();


                        $bookingResult = $conn->query(
                            "SELECT service_id FROM bookings
                             WHERE booking_id = " . $review["booking_id"]
                        );

                        $booking = $bookingResult->fetch_assoc();


                        $serviceName = "Home Service";

                        if ($booking) {

                            $serviceResult = $conn->query(
                                "SELECT service_name FROM services
                                 WHERE service_id = " . $booking["service_id"]
                            );

                            $service = $serviceResult->fetch_assoc();

                            if ($service) {
                                $serviceName = $service["service_name"];
                            }
                        }
                        ?>

                        <div class="testimonial-card">

                            <div class="rating">

                                <?php

                                $rating = $review["rating"];

                                for ($i = 1; $i <= 5; $i++) {

                                    if ($i <= $rating) {
                                        echo "★";
                                    } else {
                                        echo "☆";
                                    }

                                }

                                ?>

                            </div>

                            <p class="review-text">

                                "<?php echo htmlspecialchars($review["review_comment"]); ?>"

                            </p>

                            <div class="review-user">

                                <strong>
                                    <?php echo htmlspecialchars($user["full_name"] ?? "Customer"); ?>
                                </strong>

                                <span>
                                    <?php echo htmlspecialchars($serviceName); ?>
                                </span>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p class="no-data">
                        No customer reviews available yet.
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </section>


</main>


<?php include "includes/footer.php"; ?>