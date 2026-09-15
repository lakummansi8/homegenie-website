<?php
$pageTitle = "Edit Service Provider";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$providerId = (int)($_GET["id"] ?? $_POST["provider_id"] ?? 0);
if ($providerId <= 0) {
    header("Location: service-providers.php?error=invalid_provider");
    exit;
}

$errors = [];

$stmt = $conn->prepare("SELECT * FROM service_providers WHERE provider_id = ? LIMIT 1");
if (!$stmt) {
    header("Location: service-providers.php?error=provider_not_found");
    exit;
}
$stmt->bind_param("i", $providerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: service-providers.php?error=provider_not_found");
    exit;
}
$provider = $result->fetch_assoc();
$stmt->close();

$fullName = $provider["full_name"];
$email = $provider["email"];
$phone = $provider["phone"];
$gender = $provider["gender"];
$experience = $provider["experience"];
$address = $provider["address"];
$area = $provider["area"];
$city = $provider["city"];
$availability = $provider["availability"];
$accountStatus = $provider["account_status"];
$categoryId = $provider["category_id"];
$currentProfileImage = $provider["profile_image"];

$categories = [];
$categoryQuery = "SELECT category_id, category_name FROM categories WHERE category_status = 'Active' ORDER BY category_name ASC";
$categoryResult = $conn->query($categoryQuery);
if ($categoryResult) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $gender = trim($_POST["gender"] ?? "");
    $experience = trim($_POST["experience"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $area = trim($_POST["area"] ?? "");
    $city = trim($_POST["city"] ?? "");
    $availability = trim($_POST["availability"] ?? "");
    $accountStatus = trim($_POST["account_status"] ?? "");
    $categoryId = (int)($_POST["category_id"] ?? 0);

    if ($fullName === "") $errors[] = "Full name is required.";
    if ($email === "") $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
    
    if ($phone === "") $errors[] = "Phone number is required.";
    if ($password !== "" && strlen($password) < 6) $errors[] = "New password must be at least 6 characters.";
    if ($categoryId <= 0) $errors[] = "Please select a category.";
    if ($experience !== "" && !is_numeric($experience)) $errors[] = "Experience must be a valid number.";
    
    if (!in_array($availability, ["Available", "Busy", "Offline"], true)) $errors[] = "Invalid availability selected.";
    if (!in_array($accountStatus, ["Pending", "Active", "Blocked"], true)) $errors[] = "Invalid account status selected.";

    if ($categoryId > 0) {
        $categoryCheck = $conn->prepare("SELECT category_id FROM categories WHERE category_id = ? AND category_status = 'Active' LIMIT 1");
        if ($categoryCheck) {
            $categoryCheck->bind_param("i", $categoryId);
            $categoryCheck->execute();
            if ($categoryCheck->get_result()->num_rows === 0) $errors[] = "Selected category is invalid.";
            $categoryCheck->close();
        } else {
            $errors[] = "Unable to verify selected category.";
        }
    }

    if ($email !== "") {
        $emailCheck = $conn->prepare("SELECT provider_id FROM service_providers WHERE email = ? AND provider_id != ? LIMIT 1");
        if ($emailCheck) {
            $emailCheck->bind_param("si", $email, $providerId);
            $emailCheck->execute();
            if ($emailCheck->get_result()->num_rows > 0) $errors[] = "Another service provider already uses this email.";
            $emailCheck->close();
        }
    }

    $newProfileImage = null;
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES["profile_image"]["error"] !== UPLOAD_ERR_OK) {
            $errors[] = "There was an error uploading the profile image.";
        } else {
            $fileSize = $_FILES["profile_image"]["size"];
            $fileTmp = $_FILES["profile_image"]["tmp_name"];
            $fileName = $_FILES["profile_image"]["name"];
            $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions, true)) $errors[] = "Profile image must be JPG, JPEG, PNG, or WEBP.";
            if ($fileSize > 5 * 1024 * 1024) $errors[] = "Profile image must not exceed 5 MB.";

            if (empty($errors)) {
                $imageInfo = getimagesize($fileTmp);
                if ($imageInfo === false) $errors[] = "The uploaded profile image is not valid.";
            }

            if (empty($errors)) {
                $newProfileImage = uniqid("provider_", true) . "." . $fileExtension;
            }
        }
    }

    if (empty($errors)) {
        $experienceValue = ($experience === "" ? null : (int)$experience);
        $updateStmt = null;

        if ($password !== "") {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if ($newProfileImage !== null) {
                $updateStmt = $conn->prepare("UPDATE service_providers SET full_name = ?, email = ?, phone = ?, password = ?, gender = ?, experience = ?, address = ?, area = ?, city = ?, availability = ?, account_status = ?, profile_image = ?, category_id = ? WHERE provider_id = ?");
                $updateStmt->bind_param("sssssiisssssii", $fullName, $email, $phone, $hashedPassword, $gender, $experienceValue, $address, $area, $city, $availability, $accountStatus, $newProfileImage, $categoryId, $providerId);
            } else {
                $updateStmt = $conn->prepare("UPDATE service_providers SET full_name = ?, email = ?, phone = ?, password = ?, gender = ?, experience = ?, address = ?, area = ?, city = ?, availability = ?, account_status = ?, category_id = ? WHERE provider_id = ?");
                $updateStmt->bind_param("sssssiissssii", $fullName, $email, $phone, $hashedPassword, $gender, $experienceValue, $address, $area, $city, $availability, $accountStatus, $categoryId, $providerId);
            }
        } else {
            if ($newProfileImage !== null) {
                $updateStmt = $conn->prepare("UPDATE service_providers SET full_name = ?, email = ?, phone = ?, gender = ?, experience = ?, address = ?, area = ?, city = ?, availability = ?, account_status = ?, profile_image = ?, category_id = ? WHERE provider_id = ?");
                $updateStmt->bind_param("ssssissssssii", $fullName, $email, $phone, $gender, $experienceValue, $address, $area, $city, $availability, $accountStatus, $newProfileImage, $categoryId, $providerId);
            } else {
                $updateStmt = $conn->prepare("UPDATE service_providers SET full_name = ?, email = ?, phone = ?, gender = ?, experience = ?, address = ?, area = ?, city = ?, availability = ?, account_status = ?, category_id = ? WHERE provider_id = ?");
                $updateStmt->bind_param("ssssisssssii", $fullName, $email, $phone, $gender, $experienceValue, $address, $area, $city, $availability, $accountStatus, $categoryId, $providerId);
            }
        }

        if ($updateStmt) {
            if ($updateStmt->execute()) {
                $updateStmt->close();
                if ($newProfileImage !== null) {
                    $uploadDirectory = "../../../assets/providers/";
                    if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0755, true);
                    $uploadPath = $uploadDirectory . $newProfileImage;
                    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $uploadPath)) {
                        if (!empty($currentProfileImage) && file_exists($uploadDirectory . $currentProfileImage)) {
                            unlink($uploadDirectory . $currentProfileImage);
                        }
                    }
                }
                header("Location: service-providers.php?success=provider_updated");
                exit;
            } else {
                $errors[] = "Unable to update service provider.";
                $updateStmt->close();
            }
        } else {
            $errors[] = "Unable to prepare service provider update.";
        }
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Edit Service Provider</h3>
        <p class="text-muted">Update the service provider's account and professional details.</p>
    </div>
    <div class="header-actions">
        <a href="service-providers.php" class="btn btn-secondary">&larr; Back to Providers</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Provider Details</strong>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="provider_id" value="<?= (int)$providerId ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($fullName) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number *</label>
                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>" maxlength="15" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="password">
                    <div class="form-text">Leave blank to keep the current password.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int)$category["category_id"] ?>" <?= (int)$categoryId === (int)$category["category_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($category["category_name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Experience (Years)</label>
                    <input type="number" class="form-control" name="experience" value="<?= htmlspecialchars($experience) ?>" min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" <?= $gender === "Male" ? "selected" : "" ?>>Male</option>
                        <option value="Female" <?= $gender === "Female" ? "selected" : "" ?>>Female</option>
                        <option value="Other" <?= $gender === "Other" ? "selected" : "" ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Availability</label>
                    <select class="form-select" name="availability">
                        <option value="Available" <?= $availability === "Available" ? "selected" : "" ?>>Available</option>
                        <option value="Busy" <?= $availability === "Busy" ? "selected" : "" ?>>Busy</option>
                        <option value="Offline" <?= $availability === "Offline" ? "selected" : "" ?>>Offline</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($address) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Area</label>
                    <input type="text" class="form-control" name="area" value="<?= htmlspecialchars($area) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($city) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Account Status</label>
                    <select class="form-select" name="account_status">
                        <option value="Pending" <?= $accountStatus === "Pending" ? "selected" : "" ?>>Pending</option>
                        <option value="Active" <?= $accountStatus === "Active" ? "selected" : "" ?>>Active</option>
                        <option value="Blocked" <?= $accountStatus === "Blocked" ? "selected" : "" ?>>Blocked</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" class="form-control" name="profile_image" accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">Leave blank to keep existing image. Max 5 MB.</div>
                    
                    <?php if (!empty($currentProfileImage)): ?>
                        <div class="mt-2">
                            <img src="../../../assets/providers/<?= htmlspecialchars($currentProfileImage) ?>" alt="Profile" class="img-thumbnail img-preview-sm">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            <div class="admin-form-actions">
                <a href="service-providers.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Service Provider</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
