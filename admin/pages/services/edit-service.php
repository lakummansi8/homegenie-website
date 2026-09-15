<?php
$pageTitle = "Edit Service";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$serviceId = (int)($_GET["id"] ?? $_POST["service_id"] ?? 0);
if ($serviceId <= 0) {
    header("Location: services.php?error=invalid_service");
    exit;
}

$error = "";
$service = null;

$stmt = $conn->prepare("SELECT * FROM services WHERE service_id = ? LIMIT 1");
$stmt->bind_param("i", $serviceId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: services.php?error=service_not_found");
    exit;
}
$service = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceName = trim($_POST["service_name"] ?? "");
    $categoryId = (int)($_POST["category_id"] ?? 0);
    $providerId = (int)($_POST["provider_id"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $serviceStatus = trim($_POST["service_status"] ?? "");

    if ($serviceName === "") $error = "Service name is required.";
    elseif (strlen($serviceName) < 2) $error = "Service name must contain at least 2 characters.";
    elseif ($categoryId <= 0) $error = "Please select a category.";
    elseif ($providerId <= 0) $error = "Please select a provider.";
    elseif ($description === "") $error = "Service description is required.";
    elseif ($price === "" || !is_numeric($price) || (float)$price < 0) $error = "Please enter a valid hourly price.";
    elseif (!in_array(strtolower($serviceStatus), ["active", "inactive"], true)) $error = "Invalid service status.";

    if ($error === "") {
        $categoryCheck = $conn->prepare("SELECT category_id FROM categories WHERE category_id = ? LIMIT 1");
        $categoryCheck->bind_param("i", $categoryId);
        $categoryCheck->execute();
        if ($categoryCheck->get_result()->num_rows === 0) $error = "Selected category does not exist.";
        $categoryCheck->close();
    }

    if ($error === "") {
        $providerCheck = $conn->prepare("SELECT provider_id FROM service_providers WHERE provider_id = ? LIMIT 1");
        $providerCheck->bind_param("i", $providerId);
        $providerCheck->execute();
        if ($providerCheck->get_result()->num_rows === 0) $error = "Selected provider does not exist.";
        $providerCheck->close();
    }

    $newImageName = $service["service_image"];
    $oldImageName = $service["service_image"];

    if ($error === "" && isset($_FILES["service_image"]) && $_FILES["service_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES["service_image"]["error"] !== UPLOAD_ERR_OK) {
            $error = "There was an error uploading the service image.";
        } else {
            $fileSize = $_FILES["service_image"]["size"];
            if ($fileSize > 5 * 1024 * 1024) {
                $error = "Service image must be smaller than 5 MB.";
            } else {
                $tmpName = $_FILES["service_image"]["tmp_name"];
                $originalName = $_FILES["service_image"]["name"];
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowedExtensions = ["jpg", "jpeg", "png", "webp"];

                if (!in_array($extension, $allowedExtensions, true)) {
                    $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";
                } else {
                    $mimeType = mime_content_type($tmpName);
                    $allowedMimeTypes = ["image/jpeg", "image/png", "image/webp"];

                    if (!in_array($mimeType, $allowedMimeTypes, true)) {
                        $error = "Invalid image file.";
                    } else {
                        $uploadDirectory = "../../../assets/services/";
                        if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0755, true);

                        $newImageName = "service_" . time() . "_" . bin2hex(random_bytes(5)) . "." . $extension;
                        $uploadPath = $uploadDirectory . $newImageName;

                        if (!move_uploaded_file($tmpName, $uploadPath)) {
                            $error = "Failed to save the uploaded image.";
                            $newImageName = $oldImageName;
                        }
                    }
                }
            }
        }
    }

    if ($error === "") {
        $priceValue = (float)$price;
        $serviceStatus = ucfirst(strtolower($serviceStatus));
        $updateStmt = $conn->prepare("UPDATE services SET category_id = ?, provider_id = ?, service_name = ?, description = ?, price = ?, service_image = ?, service_status = ? WHERE service_id = ?");
        $updateStmt->bind_param("iissdssi", $categoryId, $providerId, $serviceName, $description, $priceValue, $newImageName, $serviceStatus, $serviceId);

        if ($updateStmt->execute()) {
            $updateStmt->close();
            if ($newImageName !== $oldImageName && !empty($oldImageName)) {
                $oldImagePath = "../../../assets/services/" . $oldImageName;
                if (file_exists($oldImagePath) && is_file($oldImagePath)) unlink($oldImagePath);
            }
            header("Location: services.php?success=service_updated");
            exit;
        } else {
            if ($newImageName !== $oldImageName && !empty($newImageName)) {
                $newImagePath = "../../../assets/services/" . $newImageName;
                if (file_exists($newImagePath) && is_file($newImagePath)) unlink($newImagePath);
            }
            $error = "Failed to update service.";
            $updateStmt->close();
        }
    }

    if ($error !== "") {
        $service["service_name"] = $serviceName;
        $service["category_id"] = $categoryId;
        $service["provider_id"] = $providerId;
        $service["description"] = $description;
        $service["price"] = $price;
        $service["service_status"] = $serviceStatus;
    }
}

$categories = $conn->query("SELECT category_id, category_name FROM categories WHERE category_status = 'Active' ORDER BY category_name ASC");
$providers = $conn->query("SELECT provider_id, full_name FROM service_providers ORDER BY full_name ASC");

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Edit Service</h3>
        <p class="text-muted">Update the service information below.</p>
    </div>
    <div class="header-actions">
        <a href="services.php" class="btn btn-secondary">&larr; Back to Services</a>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Service Details</strong>
    </div>
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="service_id" value="<?= (int)$service["service_id"] ?>">

            <div class="mb-3">
                <label for="service_name" class="form-label">Service Name *</label>
                <input type="text" class="form-control" id="service_name" name="service_name" value="<?= htmlspecialchars($service["service_name"]) ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php if ($categories && $categories->num_rows > 0): ?>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?= (int)$category["category_id"] ?>" <?= ((int)$category["category_id"] === (int)$service["category_id"]) ? "selected" : "" ?>>
                                    <?= htmlspecialchars($category["category_name"]) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="provider_id" class="form-label">Provider *</label>
                    <select class="form-select" id="provider_id" name="provider_id" required>
                        <option value="">Select Provider</option>
                        <?php if ($providers && $providers->num_rows > 0): ?>
                            <?php while ($provider = $providers->fetch_assoc()): ?>
                                <option value="<?= (int)$provider["provider_id"] ?>" <?= ((int)$provider["provider_id"] === (int)$service["provider_id"]) ? "selected" : "" ?>>
                                    <?= htmlspecialchars($provider["full_name"]) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Hourly Price (₹) *</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($service["price"]) ?>" min="0" step="0.01" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_status" class="form-label">Status *</label>
                    <select class="form-select" id="service_status" name="service_status" required>
                        <option value="Active" <?= (strtolower($service["service_status"]) === "active") ? "selected" : "" ?>>Active</option>
                        <option value="Inactive" <?= (strtolower($service["service_status"]) === "inactive") ? "selected" : "" ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($service["description"]) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="service_image" class="form-label">Service Image</label>
                <input type="file" class="form-control" id="service_image" name="service_image" accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Leave empty to keep the existing image. JPG, PNG or WEBP. Maximum 5 MB.</div>
            </div>

            <?php if (!empty($service["service_image"])): ?>
                <div class="mb-3">
                    <img src="../../../assets/services/<?= htmlspecialchars($service["service_image"]) ?>" alt="Current Image" class="img-thumbnail img-preview-lg">
                </div>
            <?php endif; ?>

            <hr>
            <div class="admin-form-actions">
                <a href="services.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Service</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
