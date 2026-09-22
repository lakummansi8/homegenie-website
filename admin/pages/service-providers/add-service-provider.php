<?php

$pageTitle = "Add Service Provider";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$errors = "";

$fullName = "";
$email = "";
$phone = "";
$gender = "";
$experience = "";
$address = "";
$area = "";
$city = "";
$availability = "Available";
$accountStatus = "Pending";
$categoryId = "";


// Get categories
$q = "select * from categories where category_status = 'Active'";
$categoryResult = mysqli_query($conn,$q);

$categories = [];

while($category = mysqli_fetch_array($categoryResult))
{
    $categories[] = $category;
}


// Form submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullName = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $gender = $_POST["gender"];
    $experience = $_POST["experience"];
    $address = $_POST["address"];
    $area = $_POST["area"];
    $city = $_POST["city"];
    $availability = $_POST["availability"];
    $accountStatus = $_POST["account_status"];
    $categoryId = $_POST["category_id"];


    // Basic validation
    if($fullName == "")
    {
        $errors = "Full name is required.";
    }
    elseif($email == "")
    {
        $errors = "Email is required.";
    }
    elseif($phone == "")
    {
        $errors = "Phone number is required.";
    }
    elseif($password == "")
    {
        $errors = "Password is required.";
    }
    elseif(strlen($password) < 6)
    {
        $errors = "Password must be at least 6 characters.";
    }
    elseif($categoryId == "")
    {
        $errors = "Please select a category.";
    }


    // Check email
    if($errors == "")
    {
        $q = "select * from service_providers where email = '$email'";
        $res = mysqli_query($conn,$q);

        if(mysqli_num_rows($res) > 0)
        {
            $errors = "A service provider with this email already exists.";
        }
    }


    // Add provider
    if($errors == "")
    {
        $profileImage = "";

        if($_FILES["profile_image"]["name"] != "")
        {
            $imageName = $_FILES["profile_image"]["name"];

            move_uploaded_file(
                $_FILES["profile_image"]["tmp_name"],
                "../../../assets/providers/" . $imageName
            );

            $profileImage = $imageName;
        }


        $password = password_hash($password,PASSWORD_DEFAULT);


        $q = "insert into service_providers
        (
            full_name,
            email,
            phone,
            password,
            gender,
            experience,
            address,
            area,
            city,
            availability,
            account_status,
            profile_image,
            category_id
        )
        values
        (
            '$fullName',
            '$email',
            '$phone',
            '$password',
            '$gender',
            '$experience',
            '$address',
            '$area',
            '$city',
            '$availability',
            '$accountStatus',
            '$profileImage',
            '$categoryId'
        )";


        $res = mysqli_query($conn,$q);


        if($res)
        {
            header("Location: service-providers.php?success=provider_added");
            exit;
        }
        else
        {
            $errors = "Unable to add service provider.";
        }
    }
}


ob_start();

?>

<div class="row mb-4">
    <div class="col-md-8">
        <h3>Add Service Provider</h3>
        <p class="text-muted">Add a new service provider to HomeGenie.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="service-providers.php" class="btn btn-secondary">&larr; Back to Service Providers</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            <li><?php print $errors; ?></li>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Service Provider Information</strong>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($fullName) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required autocomplete="new-email">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number *</label>
                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>" maxlength="15" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" class="form-control" name="password" required autocomplete="new-password">
                    <div class="form-text">At least 6 characters.</div>
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
                    <div class="form-text">JPG, PNG, WEBP. Max 5 MB.</div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="service-providers.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Service Provider</button>
            </div>
        </form>
    </div>
</div>

<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>