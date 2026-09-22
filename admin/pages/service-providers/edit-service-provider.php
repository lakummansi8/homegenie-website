<?php
$pageTitle = "Edit Service Provider";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$providerId = $_GET["id"];

if($providerId == "")
{
    header("Location: service-providers.php?error=invalid_provider");
    exit;
}

$q = "select * from service_providers where provider_id = $providerId";
$res = mysqli_query($conn,$q);

if(mysqli_num_rows($res) == 0)
{
    header("Location: service-providers.php?error=provider_not_found");
    exit;
}

$provider = mysqli_fetch_array($res);

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

$errors = array();

$q = "select * from categories where category_status = 'Active'";
$categoryResult = mysqli_query($conn,$q);

$categories = array();

while($category = mysqli_fetch_array($categoryResult))
{
    $categories[] = $category;
}


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

    if($fullName == "")
    {
        $errors[] = "Full name is required.";
    }
    elseif($email == "")
    {
        $errors[] = "Email is required.";
    }
    elseif($phone == "")
    {
        $errors[] = "Phone number is required.";
    }
    elseif($categoryId == "")
    {
        $errors[] = "Please select a category.";
    }
    elseif($availability != "Available" && $availability != "Busy" && $availability != "Offline")
    {
        $errors[] = "Invalid availability selected.";
    }
    elseif($accountStatus != "Pending" && $accountStatus != "Active" && $accountStatus != "Blocked")
    {
        $errors[] = "Invalid account status selected.";
    }

    if($password != "" && strlen($password) < 6)
    {
        $errors[] = "New password must be at least 6 characters.";
    }

    if($experience != "" && !is_numeric($experience))
    {
        $errors[] = "Experience must be a valid number.";
    }


    if(count($errors) == 0)
    {
        $q = "select * from service_providers
              where email = '$email'
              and provider_id != $providerId";

        $emailResult = mysqli_query($conn,$q);

        if(mysqli_num_rows($emailResult) > 0)
        {
            $errors[] = "Another service provider already uses this email.";
        }
    }


    $newProfileImage = $currentProfileImage;

    if($_FILES["profile_image"]["name"] != "")
    {
        $imageName = $_FILES["profile_image"]["name"];

        move_uploaded_file(
            $_FILES["profile_image"]["tmp_name"],
            "../../../assets/providers/" . $imageName
        );

        $newProfileImage = $imageName;
    }


    if(count($errors) == 0)
    {
        if($password != "")
        {
            $password = password_hash($password,PASSWORD_DEFAULT);
        }
        else
        {
            $password = $provider["password"];
        }

        $q = "update service_providers set
              full_name='$fullName',
              email='$email',
              phone='$phone',
              password='$password',
              gender='$gender',
              experience='$experience',
              address='$address',
              area='$area',
              city='$city',
              availability='$availability',
              account_status='$accountStatus',
              profile_image='$newProfileImage',
              category_id='$categoryId'
              where provider_id=$providerId";

        $res = mysqli_query($conn,$q);

        if($res)
        {
            if(
                $currentProfileImage != "" &&
                $newProfileImage != $currentProfileImage
            )
            {
                $oldImage = "../../../assets/providers/" . $currentProfileImage;

                if(file_exists($oldImage))
                {
                    unlink($oldImage);
                }
            }

            header("Location: service-providers.php?success=provider_updated");
            exit;
        }
        else
        {
            $errors[] = "Unable to update service provider.";
        }
    }
}


ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h3>Edit Service Provider</h3>
        <p class="text-muted">Update the service provider's account and professional details.</p>
    </div>
    <div class="col-md-4 text-md-end">
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
                            <img src="../../../assets/providers/<?= htmlspecialchars($currentProfileImage) ?>" alt="Profile" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
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