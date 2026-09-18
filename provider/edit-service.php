<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$serviceId = $_GET["id"] ?? "";

if ($serviceId === "" || !is_numeric($serviceId)) {
    header("Location: services.php");
    exit;
}

$serviceId = (int)$serviceId;

$stmt = $conn->prepare(
    "SELECT
        s.service_id,
        s.service_name,
        s.description,
        s.price,
        s.service_status,
        s.category_id,
        c.category_name
     FROM services s
     LEFT JOIN categories c
        ON s.category_id = c.category_id
     WHERE s.service_id = ?
     AND s.provider_id = ?
     LIMIT 1"
);

$stmt->bind_param("ii", $serviceId, $providerId);
$stmt->execute();

$result = $stmt->get_result();
$service = $result->fetch_assoc();

$stmt->close();

if (!$service) {
    header("Location: services.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $serviceName = trim($_POST["service_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $serviceStatus = trim($_POST["service_status"] ?? "");

    if (
        $serviceName === "" ||
        $description === "" ||
        $price === "" ||
        $serviceStatus === ""
    ) {
        $error = "Please fill in all fields.";
    } elseif (!is_numeric($price) || $price < 0) {
        $error = "Please enter a valid price.";
    } elseif (!in_array($serviceStatus, ["Active", "Inactive"])) {
        $error = "Invalid service status.";
    } else {

        $stmt = $conn->prepare(
            "UPDATE services
             SET
                service_name = ?,
                description = ?,
                price = ?,
                service_status = ?
             WHERE service_id = ?
             AND provider_id = ?"
        );

        $stmt->bind_param(
            "ssdsii",
            $serviceName,
            $description,
            $price,
            $serviceStatus,
            $serviceId,
            $providerId
        );

        if ($stmt->execute()) {

            $stmt->close();

            header("Location: services.php?updated=1");
            exit;

        } else {

            $error = "Failed to update service.";
        }

        $stmt->close();
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

        <form method="POST" action="edit-service.php?id=<?php echo $serviceId; ?>">

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
                        value="<?php echo htmlspecialchars($service["category_name"] ?? "Not Assigned"); ?>"
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
                            <?php echo $service["service_status"] === "Active" ? "selected" : ""; ?>
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            <?php echo $service["service_status"] === "Inactive" ? "selected" : ""; ?>
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