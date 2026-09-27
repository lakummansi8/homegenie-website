<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


$providerId = $_GET["provider_id"];

$serviceId = $_GET["service_id"];


/* Get Provider */

$q = "select * from service_providers
      where provider_id = $providerId";

$result = mysqli_query($conn, $q);


if (mysqli_num_rows($result) == 0)
{
    echo "Provider not found.";
    exit;
}


$provider = mysqli_fetch_array($result);


/* Get Service */

$q2 = "select * from services
       where service_id = $serviceId";

$serviceResult = mysqli_query($conn, $q2);


$service = mysqli_fetch_array($serviceResult);


$pageTitle = "Provider Details";

$pageCss = "provider-details.css";


ob_start();

?>


<div class="provider-details-page">


    <div class="provider-details-card">


        <div class="provider-details-header">

            <h1>
                Provider Details
            </h1>

            <p>
                Complete information about this service provider.
            </p>

        </div>


        <div class="provider-details-body">


            <!-- Provider Name -->

            <div class="provider-main">

                <h2>
                    <?php
                    echo htmlspecialchars(
                        $provider["full_name"]
                    );
                    ?>
                </h2>

                <p>
                    Service Provider
                </p>

            </div>


            <!-- Service Information -->

            <div class="details-section">

                <h3>
                    Service Information
                </h3>


                <div class="details-grid">


                    <div class="details-item">

                        <span>
                            Service
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $service["service_name"]
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="details-item">

                        <span>
                            Price
                        </span>

                        <strong>
                            ₹<?php
                            echo number_format(
                                $service["price"],
                                2
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="details-item">

                        <span>
                            Experience
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["experience"]
                            );
                            ?>
                            Years
                        </strong>

                    </div>


                    <div class="details-item">

                        <span>
                            Availability
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["availability"]
                            );
                            ?>
                        </strong>

                    </div>


                </div>

            </div>


            <!-- Contact Information -->

            <div class="details-section">

                <h3>
                    Contact Information
                </h3>


                <div class="details-grid">


                    <div class="details-item">

                        <span>
                            Email
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["email"]
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="details-item">

                        <span>
                            Phone
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["phone"]
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="details-item">

                        <span>
                            Gender
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["gender"]
                            );
                            ?>
                        </strong>

                    </div>


                </div>

            </div>


            <!-- Location -->

            <div class="details-section">

                <h3>
                    Location
                </h3>


                <div class="details-grid">


                    <div class="details-item">

                        <span>
                            Area
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["area"]
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="details-item">

                        <span>
                            City
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["city"]
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="details-item address-item">

                        <span>
                            Address
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $provider["address"]
                            );
                            ?>
                        </strong>

                    </div>


                </div>

            </div>


            <!-- Buttons -->

            <div class="provider-details-buttons">


                <a
                    href="book-service.php?service_id=<?php echo $serviceId; ?>"
                    class="book-service-button"
                >
                    Book Service
                </a>


                <a
                    href="services.php"
                    class="back-button"
                >
                    Back to Services
                </a>


            </div>


        </div>


    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "layout/customer-layout.php";

?>