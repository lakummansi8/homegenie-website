<?php
$pageTitle = "Add Customer";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$fullName = "";
$email = "";
$phone = "";
$password = "";
$address = "";
$city = "";
$accountStatus = "Active";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $address = trim($_POST["address"] ?? "");
    $city = trim($_POST["city"] ?? "");
    $accountStatus = trim($_POST["account_status"] ?? "Active");

    if ($fullName === "") $errors[] = "Customer name is required.";
    if ($email === "") $errors[] = "Email address is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
    
    if ($phone === "") $errors[] = "Phone number is required.";
    if ($password === "") $errors[] = "Password is required.";
    elseif (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    
    if ($address === "") $errors[] = "Address is required.";
    if ($city === "") $errors[] = "City is required.";

    $allowedStatuses = ["Active", "Blocked"];
    if (!in_array($accountStatus, $allowedStatuses, true)) $errors[] = "Invalid account status.";

    if (empty($errors)) {
        $emailCheck = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
        if ($emailCheck) {
            $emailCheck->bind_param("s", $email);
            $emailCheck->execute();
            if ($emailCheck->get_result()->num_rows > 0) $errors[] = "A customer with this email address already exists.";
            $emailCheck->close();
        } else {
            $errors[] = "Unable to verify email address.";
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, address, city, account_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sssssss", $fullName, $email, $phone, $hashedPassword, $address, $city, $accountStatus);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: users.php?success=user_added");
                exit;
            } else {
                $errors[] = "Unable to add customer. Please try again.";
            }
            $stmt->close();
        } else {
            $errors[] = "Unable to prepare customer registration.";
        }
    }
}

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Add Customer</h3>
        <p class="text-muted">Create a new HomeGenie customer account.</p>
    </div>
    <div class="header-actions">
        <a href="users.php" class="btn btn-secondary">&larr; Back to Customers</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following:</strong>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Account Details</strong>
    </div>
    <div class="card-body">
        <form method="POST" autocomplete="off">
            <h5 class="mb-3">Basic Information</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($fullName) ?>" required maxlength="100">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required autocomplete="new-email" maxlength="150">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>" required maxlength="20">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" class="form-control" name="password" required autocomplete="new-password" minlength="6">
                    <div class="form-text">Minimum 6 characters.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">City *</label>
                    <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($city) ?>" required maxlength="100">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Account Status *</label>
                    <select class="form-select" name="account_status" required>
                        <option value="Active" <?= $accountStatus === "Active" ? "selected" : "" ?>>Active</option>
                        <option value="Blocked" <?= $accountStatus === "Blocked" ? "selected" : "" ?>>Blocked</option>
                    </select>
                </div>
            </div>

            <h5 class="mt-4 mb-3">Address</h5>
            <div class="mb-3">
                <label class="form-label">Full Address *</label>
                <textarea class="form-control" name="address" rows="4" required maxlength="500"><?= htmlspecialchars($address) ?></textarea>
            </div>

            <hr>
            <div class="admin-form-actions">
                <a href="users.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Customer</button>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
