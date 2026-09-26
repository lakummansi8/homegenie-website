<?php

$pageTitle = "Add Service Provider";
$pageCss = "service-providers.css";

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
        $errors = "Full name is required.";
    }
    elseif ($email == "")
    {
        $errors = "Email is required.";
    }
    elseif ($phone == "")
    {
        $errors = "Phone number is required.";
    }
    elseif ($password == "")
    {
        $errors = "Password is required.";
    }
    elseif (strlen($password) < 6)
    {
        $errors = "Password must be at least 6 characters.";
    }
    elseif ($categoryId == "")
    {
        $errors = "Please select a category.";
    }


    /* Check Email */

    if ($errors == "")
    {
        $q = "select * from service_providers where email = '$email'";

        $res = mysqli_query($conn, $q);

        if (mysqli_num_rows($res) > 0)
        {
            $errors = "A service provider with this email already exists.";
        }
    }


    /* Add Provider */

    if ($errors == "")
    {
        $profileImage = "";

        if ($_FILES["profile_image"]["name"] != "")
        {
            $imageName = $_FILES["profile_image"]["name"];

            move_uploaded_file(
                $_FILES["profile_image"]["tmp_name"],
                "../../../assets/providers/" . $imageName
            );

            $profileImage = $imageName;
        }


        $password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


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


        $res = mysqli_query($conn, $q);


        if ($res)
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

<div class="provider-form-page">

    <div class="provider-form-header">

        <div>

            <h1>Add Service Provider</h1>

            <p>
                Add a new service provider to HomeGenie.
            </p>

        </div>

        <a
            href="service-providers.php"
            class="provider-secondary-btn"
        >
            &larr; Back to Service Providers
        </a>

    </div>


    <?php if ($errors != ""): ?>

        <div class="provider-form-error">

            <strong>Please fix the following error:</strong>

            <p>
                <?php echo htmlspecialchars($errors); ?>
            </p>

        </div>

    <?php endif; ?>


    <div class="provider-form-card">

        <div class="provider-form-card-header">
            <strong>Service Provider Information</strong>
        </div>


        <div class="provider-form-card-body">

            <form
                method="POST"
                enctype="multipart/form-data"
                autocomplete="off"
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
                            autocomplete="off"
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

                        <label>Password *</label>

                        <input
                            type="password"
                            name="password"
                            required
                           autocomplete="new-password"
                        >

                        <small>
                            At least 6 characters.
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

                                    if ($categoryId == $category["category_id"]) {
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
                            JPG, PNG, WEBP. Max 5 MB.
                        </small>

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
                        Add Service Provider
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