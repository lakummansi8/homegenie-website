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
$error = "";

if(isset($_POST['submit']))
{
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $accountStatus = $_POST['account_status'];

    if($fullName == "")
    {
        $error = "Customer name is required.";
    }
    elseif($email == "")
    {
        $error = "Email address is required.";
    }
    elseif($phone == "")
    {
        $error = "Phone number is required.";
    }
    elseif($password == "")
    {
        $error = "Password is required.";
    }
    elseif(strlen($password) < 6)
    {
        $error = "Password must be at least 6 characters.";
    }
    elseif($address == "")
    {
        $error = "Address is required.";
    }
    elseif($city == "")
    {
        $error = "City is required.";
    }

    if($error == "")
    {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $error = "A customer with this email address already exists.";
        }
    }

    if($error == "")
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
                (full_name, email, phone, password, address, city, account_status)
                VALUES
                ('$fullName', '$email', '$phone', '$password', '$address', '$city', '$accountStatus')";

        $result = mysqli_query($conn, $sql);

        if($result)
        {
            header("Location: users.php?success=user_added");
            exit;
        }
        else
        {
            $error = "Unable to add customer. Please try again.";
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

<?php if($error != "") { ?>

    <div class="alert alert-danger">
        <strong>Please fix the following:</strong>
        <ul class="mb-0">
            <li><?php echo $error; ?></li>
        </ul>
    </div>

<?php } ?>

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
                <button type="submit" name="submit" class="btn btn-primary">Add Customer</button>
            </div>

        </form>
    </div>
</div>

<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>