<?php
$pageTitle = "Add Service";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$errors = [];
$serviceName = "";
$categoryId = "";
$providerId = "";
$description = "";
$price = "";
$serviceStatus = "Active";

$categories = [];
$categoryResult = $conn->query("SELECT category_id, category_name FROM categories WHERE category_status = 'Active' ORDER BY category_name ASC");
if ($categoryResult) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

if (isset($_GET["get_providers"])) {
    $categoryId = (int)($_GET["category_id"] ?? 0);
    $providers = [];
    if ($categoryId > 0) {
        $stmt = $conn->prepare("SELECT provider_id, full_name FROM service_providers WHERE category_id = ? AND account_status = 'Active' ORDER BY full_name ASC");
        if ($stmt) {
            $stmt->bind_param("i", $categoryId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $providers[] = $row;
            }
            $stmt->close();
        }
    }
    header("Content-Type: application/json");
    echo json_encode($providers);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceName = trim($_POST["service_name"] ?? "");
    $categoryId = (int)($_POST["category_id"] ?? 0);
    $providerId = (int)($_POST["provider_id"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $serviceStatus = $_POST["service_status"] ?? "Active";

    if ($serviceName === "") $errors[] = "Service name is required.";
    if ($categoryId <= 0) $errors[] = "Please select a category.";
    if ($providerId <= 0) $errors[] = "Please select a provider.";
    if ($description === "") $errors[] = "Description is required.";
    if ($price === "" || !is_numeric($price) || $price < 0) $errors[] = "Please enter a valid hourly price.";
    if (!in_array($serviceStatus, ["Active", "Inactive"], true)) $errors[] = "Invalid service status.";

    $serviceImage = "";
    if (isset($_FILES["service_image"]) && $_FILES["service_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES["service_image"]["error"] !== UPLOAD_ERR_OK) {
            $errors[] = "There was a problem uploading the service image.";
        } else {
            $allowedTypes = ["image/jpeg", "image/png", "image/webp"];
            $fileType = mime_content_type($_FILES["service_image"]["tmp_name"]);
            if (!in_array($fileType, $allowedTypes, true)) {
                $errors[] = "Only JPG, PNG, and WEBP images are allowed.";
            } elseif ($_FILES["service_image"]["size"] > 5 * 1024 * 1024) {
                $errors[] = "Service image must be less than 5 MB.";
            } else {
                $extension = strtolower(pathinfo($_FILES["service_image"]["name"], PATHINFO_EXTENSION));
                $serviceImage = uniqid("service_", true) . "." . $extension;
            }
        }
    }

    if (empty($errors)) {
        $uploadDirectory = "../../../assets/services/";
        if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0755, true);

        if ($serviceImage !== "") {
            $uploadPath = $uploadDirectory . $serviceImage;
            if (!move_uploaded_file($_FILES["service_image"]["tmp_name"], $uploadPath)) {
                $errors[] = "Failed to upload service image.";
            }
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO services (category_id, provider_id, service_name, description, price, service_image, service_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $priceValue = (float)$price;
                $stmt->bind_param("iissdss", $categoryId, $providerId, $serviceName, $description, $priceValue, $serviceImage, $serviceStatus);
                if ($stmt->execute()) {
                    header("Location: services.php?success=service_added");
                    exit;
                } else {
                    if ($serviceImage !== "") {
                        $uploadedFile = $uploadDirectory . $serviceImage;
                        if (file_exists($uploadedFile)) unlink($uploadedFile);
                    }
                    $errors[] = "Failed to add service.";
                }
                $stmt->close();
            } else {
                $errors[] = "Unable to prepare database query.";
            }
        }
    }
}

ob_start();
?>

<div class="admin-page-header">
       <div class="header-title">
        <h3>Add Service</h3>
        <p class="text-muted">Add a new service to your HomeGenie service list.</p>
    </div>
    <div class="header-actions">
        <a href="services.php" class="btn btn-secondary">&larr; Back to Services</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Service Details</strong>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="service_name" class="form-label">Service Name *</label>
                <input type="text" class="form-control" id="service_name" name="service_name" value="<?= htmlspecialchars($serviceName) ?>" required placeholder="Enter service name">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int)$category["category_id"] ?>" <?= $categoryId == $category["category_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($category["category_name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="provider_id" class="form-label">Provider *</label>
                    <select class="form-select" id="provider_id" name="provider_id" required disabled>
                        <option value="">Select Category First</option>
                    </select>
                    <div class="form-text">Only providers belonging to the selected category will be shown.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Enter service description"><?= htmlspecialchars($description) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Hourly Price (₹) *</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($price) ?>" placeholder="499" min="0" step="0.01" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_status" class="form-label">Status *</label>
                    <select class="form-select" id="service_status" name="service_status" required>
                        <option value="Active" <?= $serviceStatus === "Active" ? "selected" : "" ?>>Active</option>
                        <option value="Inactive" <?= $serviceStatus === "Inactive" ? "selected" : "" ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="service_image" class="form-label">Service Image</label>
                <input type="file" class="form-control" id="service_image" name="service_image" accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Allowed formats: JPG, PNG, WEBP. Maximum size: 5 MB.</div>
            </div>

            <hr>
            <div class="admin-form-actions">
                <a href="services.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Service</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById("category_id");
    const providerSelect = document.getElementById("provider_id");

    categorySelect.addEventListener("change", function () {
        const categoryId = this.value;
        providerSelect.innerHTML = "";
        
        if (!categoryId) {
            providerSelect.disabled = true;
            providerSelect.innerHTML = `<option value="">Select Category First</option>`;
            return;
        }

        providerSelect.disabled = true;
        providerSelect.innerHTML = `<option value="">Loading Providers...</option>`;

        fetch(`add-service.php?get_providers=1&category_id=${encodeURIComponent(categoryId)}`)
        .then(response => {
            if (!response.ok) throw new Error("Failed to fetch providers.");
            return response.json();
        })
        .then(providers => {
            providerSelect.innerHTML = "";
            if (providers.length === 0) {
                providerSelect.disabled = true;
                providerSelect.innerHTML = `<option value="">No providers available</option>`;
                return;
            }
            providerSelect.disabled = false;
            providerSelect.innerHTML = `<option value="">Select Provider</option>`;
            providers.forEach(provider => {
                const option = document.createElement("option");
                option.value = provider.provider_id;
                option.textContent = provider.full_name;
                providerSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error(error);
            providerSelect.disabled = true;
            providerSelect.innerHTML = `<option value="">Unable to load providers</option>`;
        });
    });
});
</script>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
