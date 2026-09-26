<?php

$pageTitle = "Edit Service Provider";
$pageCss = "service-providers.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$providerId = $_GET["id"];


if ($providerId == "")
{
    header("Location: service-providers.php?error=invalid_provider");
    exit;
}


/* Get Provider */

$q = "select * from service_providers where provider_id = $providerId";

$res = mysqli_query($conn, $q);


if (mysqli_num_rows($res) == 0)
{
    header("Location: service-providers.php?error=provider_not_found");
    exit;
}


$provider = mysqli_fetch_array($res);


/* Existing Values */

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


/* Get Categories */

$q = "select * from categories where category_status = 'Active'";

$categoryResult = mysqli_query($conn, $q);

$categories = array();

while ($category = mysqli_fetch_array($categoryResult))
{
    $categories[] = $category;
}


/* Form Submitted */

if ($_SERVER["REQUEST_METHOD"] == "POST")
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


    if ($fullName == "")
    {
        $errors[] = "Full name is required.";
    }
    elseif ($email == "")
    {
        $errors[] = "Email is required.";
    }
    elseif ($phone == "")
    {
        $errors[] = "Phone number is required.";
    }
    elseif ($categoryId == "")
    {
        $errors[] = "Please select a category.";
    }
    elseif (
        $availability != "Available" &&
        $availability != "Busy" &&
        $availability != "Offline"
    )
    {
        $errors[] = "Invalid availability selected.";
    }
    elseif (
        $accountStatus != "Pending" &&
        $accountStatus != "Active" &&
        $accountStatus != "Blocked"
    )
    {
        $errors[] = "Invalid account status selected.";
    }


    if ($password != "" && strlen($password) < 6)
    {
        $errors[] = "New password must be at least 6 characters.";
    }


    if ($experience != "" && !is_numeric($experience))
    {
        $errors[] = "Experience must be a valid number.";
    }


    /* Check Email */

    if (count($errors) == 0)
    {
        $q = "select * from service_providers
              where email = '$email'
              and provider_id != $providerId";

        $emailResult = mysqli_query($conn, $q);


        if (mysqli_num_rows($emailResult) > 0)
        {
            $errors[] = "Another service provider already uses this email.";
        }
    }


    /* Profile Image */

    $newProfileImage = $currentProfileImage;


    if ($_FILES["profile_image"]["name"] != "")
    {
        $imageName = $_FILES["profile_image"]["name"];

        move_uploaded_file(
            $_FILES["profile_image"]["tmp_name"],
            "../../../assets/providers/" . $imageName
        );

        $newProfileImage = $imageName;
    }


    /* Update Provider */

    if (count($errors) == 0)
    {

        if ($password != "")
        {
            $password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
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


        $res = mysqli_query($conn, $q);


        if ($res)
        {

            if (
                $currentProfileImage != "" &&
                $newProfileImage != $currentProfileImage
            )
            {
                $oldImage =
                    "../../../assets/providers/" .
                    $currentProfileImage;


                if (file_exists($oldImage))
                {
                    unlink($oldImage);
                }
            }


            header(
                "Location: service-providers.php?success=provider_updated"
            );

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

<div class="provider-form-page">

    <div class="provider-form-header">

        <div>

            <h1>Edit Service Provider</h1>

            <p>
                Update the service provider's account and professional details.
            </p>

        </div>


        <a
            href="service-providers.php"
            class="provider-secondary-btn"
        >
            &larr; Back to Providers
        </a>

    </div>


    <?php if (!empty($errors)): ?>

        <div class="provider-form-error">

            <strong>Please fix the following errors:</strong>

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?php echo htmlspecialchars($error); ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <div class="provider-form-card">

        <div class="provider-form-card-header">
            <strong>Provider Details</strong>
        </div>


        <div class="provider-form-card-body">

            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <div class="provider-form-row">


                    <div class="provider-form-group">

                        <label>Full Name *</label>

                        <input
                            type="text"
                            name="full_name"
                            value="<?php echo htmlspecialchars($fullName); ?>"
                            required
                        >

                    </div>


                    <div class="provider-form-group">

                        <label>Email *</label>

                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >

                    </div>


                </div>


                <div class="provider-form-row">


                    <div class="provider-form-group">

                        <label>Phone Number *</label>

                        <input
                            type="text"
                            name="phone"
                            value="<?php echo htmlspecialchars($phone); ?>"
                            maxlength="15"
                            required
                        >

                    </div>


                    <div class="provider-form-group">

                        <label>New Password</label>

                        <input
                            type="password"
                            name="password"
                        >

                        <small>
                            Leave blank to keep the current password.
                        </small>

                    </div>


                </div>


                <div class="provider-form-row">


                    <div class="provider-form-group">

                        <label>Category *</label>

                        <select
                            name="category_id"
                            required
                        >

                            <option value="">
                                Select Category
                            </option>


                            <?php foreach ($categories as $category): ?>

                                <option
                                    value="<?php echo $category["category_id"]; ?>"
                                    <?php

                                    if (
                                        $categoryId ==
                                        $category["category_id"]
                                    ) {
                                        echo "selected";
                                    }

                                    ?>
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $category["category_name"]
                                    );

                                    ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="provider-form-group">

                        <label>Experience (Years)</label>

                        <input
                            type="number"
                            name="experience"
                            value="<?php echo htmlspecialchars($experience); ?>"
                            min="0"
                        >

                    </div>


                </div>


                <div class="provider-form-row">


                    <div class="provider-form-group">

                        <label>Gender</label>

                        <select name="gender">

                            <option value="">
                                Select Gender
                            </option>

                            <option
                                value="Male"
                                <?php if ($gender == "Male") echo "selected"; ?>
                            >
                                Male
                            </option>

                            <option
                                value="Female"
                                <?php if ($gender == "Female") echo "selected"; ?>
                            >
                                Female
                            </option>

                            <option
                                value="Other"
                                <?php if ($gender == "Other") echo "selected"; ?>
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    <div class="provider-form-group">

                        <label>Availability</label>

                        <select name="availability">

                            <option
                                value="Available"
                                <?php if ($availability == "Available") echo "selected"; ?>
                            >
                                Available
                            </option>

                            <option
                                value="Busy"
                                <?php if ($availability == "Busy") echo "selected"; ?>
                            >
                                Busy
                            </option>

                            <option
                                value="Offline"
                                <?php if ($availability == "Offline") echo "selected"; ?>
                            >
                                Offline
                            </option>

                        </select>

                    </div>


                </div>


                <div class="provider-form-group full-width">

                    <label>Address</label>

                    <textarea
                        name="address"
                        rows="3"
                    ><?php echo htmlspecialchars($address); ?></textarea>

                </div>


                <div class="provider-form-row">


                    <div class="provider-form-group">

                        <label>Area</label>

                        <input
                            type="text"
                            name="area"
                            value="<?php echo htmlspecialchars($area); ?>"
                        >

                    </div>


                    <div class="provider-form-group">

                        <label>City</label>

                        <input
                            type="text"
                            name="city"
                            value="<?php echo htmlspecialchars($city); ?>"
                        >

                    </div>


                </div>


                <div class="provider-form-row">


                    <div class="provider-form-group">

                        <label>Account Status</label>

                        <select name="account_status">

                            <option
                                value="Pending"
                                <?php if ($accountStatus == "Pending") echo "selected"; ?>
                            >
                                Pending
                            </option>

                            <option
                                value="Active"
                                <?php if ($accountStatus == "Active") echo "selected"; ?>
                            >
                                Active
                            </option>

                            <option
                                value="Blocked"
                                <?php if ($accountStatus == "Blocked") echo "selected"; ?>
                            >
                                Blocked
                            </option>

                        </select>

                    </div>


                    <div class="provider-form-group">

                        <label>Profile Image</label>

                        <input
                            type="file"
                            name="profile_image"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small>
                            Leave blank to keep existing image. Max 5 MB.
                        </small>


                        <?php if (!empty($currentProfileImage)): ?>

                            <div class="current-provider-image">

                                <img
                                    src="../../../assets/providers/<?php echo htmlspecialchars($currentProfileImage); ?>"
                                    alt="Profile"
                                >

                            </div>

                        <?php endif; ?>

                    </div>


                </div>


                <hr>


                <div class="provider-form-actions">

                    <a
                        href="service-providers.php"
                        class="provider-secondary-btn"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="provider-primary-btn"
                    >
                        Update Service Provider
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>