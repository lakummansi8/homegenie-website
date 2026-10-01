```php
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$serviceId = $_GET["id"] ?? "";


/* Check service ID */

if ($serviceId == "" || !is_numeric($serviceId)) {

    header("Location: services.php");
    exit;
}

$serviceId = (int)$serviceId;


/* Get service information */

$sql = "SELECT * FROM services
        WHERE service_id = $serviceId
        AND provider_id = $providerId";

$result = mysqli_query($conn, $sql);
$service = mysqli_fetch_assoc($result);


/* If service is not found */

if (!$service) {

    header("Location: services.php");
    exit;
}


/* Get category name */

$categoryId = $service["category_id"];

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


/* Update service */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $serviceName = trim($_POST["service_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $serviceStatus = trim($_POST["service_status"] ?? "");


    /* Check empty fields */

    if (
        $serviceName == "" ||
        $description == "" ||
        $price == "" ||
        $serviceStatus == ""
    ) {

        $error = "Please fill in all fields.";

    }


    /* Check price */

    elseif (!is_numeric($price) || $price < 0) {

        $error = "Please enter a valid price.";

    }


    /* Check status */

    elseif (
        $serviceStatus != "Active" &&
        $serviceStatus != "Inactive"
    ) {

        $error = "Invalid service status.";

    }


    else {

        /* Protect values before putting them in SQL */

        $serviceName = mysqli_real_escape_string($conn, $serviceName);
        $description = mysqli_real_escape_string($conn, $description);
        $price = mysqli_real_escape_string($conn, $price);
        $serviceStatus = mysqli_real_escape_string($conn, $serviceStatus);


        /* Update service */

        $sql = "UPDATE services SET
                service_name = '$serviceName',
                description = '$description',
                price = '$price',
                service_status = '$serviceStatus'
                WHERE service_id = $serviceId
                AND provider_id = $providerId";


        if (mysqli_query($conn, $sql)) {

            header("Location: services.php?updated=1");
            exit;

        } else {

            $error = "Failed to update service.";

        }
    }
}


$pageTitle = "Edit Service";
$pageCss = "services.css";

require_once "layout/provider-layout.php";
?>

<div class="edit-service-page">

    <div class="services-header">

        <div>

            <h2>Edit Service</h2>

            <p>Update your service information.</p>

        </div>

    </div>


    <?php if (isset($error)): ?>

        <div class="service-message error-message">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <div class="edit-service-card">

        <form
            method="POST"
            action="edit-service.php?id=<?php echo $serviceId; ?>"
        >

            <div class="form-grid">


                <div class="form-group">

                    <label>Service Name</label>

                    <input
                        type="text"
                        name="service_name"
                        value="<?php echo htmlspecialchars($service["service_name"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Category</label>

                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($categoryName); ?>"
                        readonly
                    >

                </div>


                <div class="form-group full-width">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        required
                    ><?php echo htmlspecialchars($service["description"]); ?></textarea>

                </div>


                <div class="form-group">

                    <label>Price</label>

                    <input
                        type="number"
                        name="price"
                        value="<?php echo htmlspecialchars($service["price"]); ?>"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Status</label>

                    <select name="service_status" required>

                        <option
                            value="Active"
                            <?php
                            if ($service["service_status"] == "Active") {
                                echo "selected";
                            }
                            ?>
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            <?php
                            if ($service["service_status"] == "Inactive") {
                                echo "selected";
                            }
                            ?>
                        >
                            Inactive
                        </option>

                    </select>

                </div>


            </div>


            <div class="form-actions">

                <a href="services.php" class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="save-button">
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

</section>
</main>
</div>

</body>
</html>

