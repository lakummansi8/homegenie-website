```php
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];


/* Get provider information */

$sql = "SELECT * FROM service_providers
        WHERE provider_id = $providerId";

$result = mysqli_query($conn, $sql);
$provider = mysqli_fetch_assoc($result);


/* Get category name */

$categoryId = $provider["category_id"];

$categorySql = "SELECT category_name
                FROM categories
                WHERE category_id = $categoryId";

$categoryResult = mysqli_query($conn, $categorySql);
$category = mysqli_fetch_assoc($categoryResult);

if ($category) {
    $categoryName = $category["category_name"];
} else {
    $categoryName = "Not Assigned";
}


/* Count services */

$serviceSql = "SELECT service_id
               FROM services
               WHERE provider_id = $providerId";

$serviceResult = mysqli_query($conn, $serviceSql);

$totalServices = mysqli_num_rows($serviceResult);


/* Get bookings */

$bookingSql = "SELECT booking_status
               FROM bookings
               WHERE provider_id = $providerId";

$bookingResult = mysqli_query($conn, $bookingSql);

$totalBookings = 0;
$pendingBookings = 0;
$confirmedBookings = 0;

while ($booking = mysqli_fetch_assoc($bookingResult)) {

    $totalBookings++;

    if ($booking["booking_status"] == "Pending") {
        $pendingBookings++;
    }

    if ($booking["booking_status"] == "Confirmed") {
        $confirmedBookings++;
    }
}


$pageTitle = "Provider Dashboard";
$pageCss = "dashboard.css";

require_once "layout/provider-layout.php";
?>

<div class="provider-dashboard">

    <div class="dashboard-welcome">

        <h2>
            Welcome, <?php echo htmlspecialchars($provider["full_name"]); ?>
        </h2>

        <p>
            Manage your services, bookings and profile from here.
        </p>

    </div>


    <div class="dashboard-cards">

        <div class="dashboard-card">

            <div class="card-content">

                <span class="card-label">My Services</span>

                <h3>
                    <?php echo $totalServices; ?>
                </h3>

            </div>

        </div>


        <div class="dashboard-card">

            <div class="card-content">

                <span class="card-label">Total Bookings</span>

                <h3>
                    <?php echo $totalBookings; ?>
                </h3>

            </div>

        </div>


        <div class="dashboard-card">

            <div class="card-content">

                <span class="card-label">Pending Bookings</span>

                <h3>
                    <?php echo $pendingBookings; ?>
                </h3>

            </div>

        </div>


        <div class="dashboard-card">

            <div class="card-content">

                <span class="card-label">Confirmed Bookings</span>

                <h3>
                    <?php echo $confirmedBookings; ?>
                </h3>

            </div>

        </div>

    </div>


    <div class="dashboard-section">

        <div class="section-header">

            <h3>My Information</h3>

        </div>


        <div class="provider-info-grid">


            <div class="info-item">

                <span>Category</span>

                <strong>
                    <?php echo htmlspecialchars($categoryName); ?>
                </strong>

            </div>


            <div class="info-item">

                <span>Experience</span>

                <strong>
                    <?php echo htmlspecialchars($provider["experience"]); ?>
                </strong>

            </div>


            <div class="info-item">

                <span>Availability</span>

                <strong>
                    <?php echo htmlspecialchars($provider["availability"]); ?>
                </strong>

            </div>


            <div class="info-item">

                <span>Account Status</span>

                <strong class="account-status">
                    <?php echo htmlspecialchars($provider["account_status"]); ?>
                </strong>

            </div>


            <div class="info-item">

                <span>Email</span>

                <strong>
                    <?php echo htmlspecialchars($provider["email"]); ?>
                </strong>

            </div>


            <div class="info-item">

                <span>Location</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $provider["area"] . ", " . $provider["city"]
                    );
                    ?>
                </strong>

            </div>


        </div>

    </div>

</div>

</section>
</main>
</div>

</body>
</html>

