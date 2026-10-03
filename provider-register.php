
<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "config/db.php";

$pageTitle = "Become a Provider";
$pageCss = "provider-register.css";


/* Get active categories */

$categoriesQuery = "
    SELECT category_id, category_name
    FROM categories
    WHERE category_status = 'Active'
    ORDER BY category_name ASC
";

$categoriesResult = mysqli_query($conn, $categoriesQuery);

if (!$categoriesResult)
{
    die("Categories query failed: " . mysqli_error($conn));
}


$message = "";
$messageType = "";


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $gender = trim($_POST["gender"] ?? "");
    $experience = (int)($_POST["experience"] ?? 0);
    $address = trim($_POST["address"] ?? "");
    $area = trim($_POST["area"] ?? "");
    $city = trim($_POST["city"] ?? "");
    $availability = trim($_POST["availability"] ?? "");
    $categoryId = (int)($_POST["category_id"] ?? 0);


    /* Check required fields */

    if (
        $fullName == "" ||
        $email == "" ||
        $phone == "" ||
        $password == "" ||
        $gender == "" ||
        $address == "" ||
        $area == "" ||
        $city == "" ||
        $availability == "" ||
        $categoryId <= 0
    )
    {
        $message = "Please fill in all required fields.";
        $messageType = "error";
    }
    else
    {
        /* Check if email already exists */

        $email = mysqli_real_escape_string($conn, $email);

        $checkQuery = "
            SELECT provider_id
            FROM service_providers
            WHERE email = '$email'
            LIMIT 1
        ";

        $checkResult = mysqli_query($conn, $checkQuery);

        if (!$checkResult)
        {
            die("Check query failed: " . mysqli_error($conn));
        }


        if (mysqli_num_rows($checkResult) > 0)
        {
            $message = "An account with this email already exists.";
            $messageType = "error";
        }
        else
        {
            /* Encrypt password */

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            /* Make text values safe */

            $fullName = mysqli_real_escape_string($conn, $fullName);
            $phone = mysqli_real_escape_string($conn, $phone);
            $gender = mysqli_real_escape_string($conn, $gender);
            $address = mysqli_real_escape_string($conn, $address);
            $area = mysqli_real_escape_string($conn, $area);
            $city = mysqli_real_escape_string($conn, $city);
            $availability = mysqli_real_escape_string($conn, $availability);


            /* Insert provider */

            $insertQuery = "
                INSERT INTO service_providers
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
                    category_id
                )
                VALUES
                (
                    '$fullName',
                    '$email',
                    '$phone',
                    '$hashedPassword',
                    '$gender',
                    $experience,
                    '$address',
                    '$area',
                    '$city',
                    '$availability',
                    'Pending',
                    $categoryId
                )
            ";


            if (mysqli_query($conn, $insertQuery))
            {
                $message = "Your provider application has been submitted successfully. Please wait for admin approval.";
                $messageType = "success";
            }
            else
            {
                $message = "Something went wrong: " . mysqli_error($conn);
                $messageType = "error";
            }
        }
    }
}

?>


<?php include "includes/header.php"; ?>


<main class="provider-register-page">


    <!-- Hero -->

    <section class="provider-hero">

        <div class="provider-hero-content">

            <span class="section-label">
                JOIN HOMEGENIE
            </span>

            <h1>
                Grow Your Business
                <span>With HomeGenie</span>
            </h1>

            <p>
                Join our network of service professionals and connect
                with customers looking for reliable home services.
            </p>

        </div>

    </section>


    <!-- Benefits -->

    <section class="provider-benefits">

        <div class="provider-container">

            <div class="section-heading">

                <span class="section-label">
                    WHY JOIN US
                </span>

                <h2>
                    Become a HomeGenie Provider
                </h2>

                <p>
                    Create your provider account and start offering
                    your services to customers.
                </p>

            </div>


            <div class="benefits-grid">

                <div class="benefit-card">

                    <h3>
                        Reach More Customers
                    </h3>

                    <p>
                        Get connected with customers looking for
                        home services in your area.
                    </p>

                </div>


                <div class="benefit-card">

                    <h3>
                        Manage Your Services
                    </h3>

                    <p>
                        Add and manage the services you provide
                        through your provider account.
                    </p>

                </div>


                <div class="benefit-card">

                    <h3>
                        Manage Bookings
                    </h3>

                    <p>
                        View customer bookings and respond to
                        service requests.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- Registration Form -->

    <section class="provider-form-section">

        <div class="provider-container">

            <div class="form-heading">

                <span class="section-label">
                    PROVIDER REGISTRATION
                </span>

                <h2>
                    Create Your Provider Account
                </h2>

                <p>
                    Fill in your details below to submit your
                    provider application.
                </p>

                <a
                    href="provider-view.php"
                    class="view-providers-link"
                >
                    View Our Service Providers
                </a>

            </div>


            <?php if ($message !== ""): ?>

                <div class="form-message <?php echo $messageType; ?>">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                class="provider-form"
            >


                <!-- Full Name + Email -->

                <div class="form-row">

                    <div class="form-group">

                        <label for="full_name">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            placeholder="Enter your full name"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>


                <!-- Phone + Password -->

                <div class="form-row">

                    <div class="form-group">

                        <label for="phone">
                            Phone
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="Enter your phone number"
                            autocomplete="off"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Create a password"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                <!-- Gender + Experience -->

                <div class="form-row">

                    <div class="form-group">

                        <label for="gender">
                            Gender
                        </label>

                        <select
                            id="gender"
                            name="gender"
                            required
                        >

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male">
                                Male
                            </option>

                            <option value="Female">
                                Female
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="experience">
                            Experience (Years)
                        </label>

                        <input
                            type="number"
                            id="experience"
                            name="experience"
                            min="0"
                            placeholder="Years of experience"
                            required
                        >

                    </div>

                </div>


                <!-- Category + Availability -->

                <div class="form-row">

                    <div class="form-group">

                        <label for="category_id">
                            Service Category
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            required
                        >

                            <option value="">
                                Select Category
                            </option>

                            <?php while ($category = mysqli_fetch_assoc($categoriesResult)): ?>

                                <option
                                    value="<?php echo (int)$category["category_id"]; ?>"
                                >
                                    <?php echo htmlspecialchars($category["category_name"]); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="availability">
                            Availability
                        </label>

                        <select
                            id="availability"
                            name="availability"
                            required
                        >

                            <option value="">
                                Select Availability
                            </option>

                            <option value="Available">
                                Available
                            </option>

                            <option value="Not Available">
                                Not Available
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Area + City -->

                <div class="form-row">

                    <div class="form-group">

                        <label for="area">
                            Area
                        </label>

                        <input
                            type="text"
                            id="area"
                            name="area"
                            placeholder="Enter your area"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="city">
                            City
                        </label>

                        <input
                            type="text"
                            id="city"
                            name="city"
                            placeholder="Enter your city"
                            required
                        >

                    </div>

                </div>


                <!-- Address -->

                <div class="form-group full-width">

                    <label for="address">
                        Full Address
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        placeholder="Enter your full address"
                        required
                    ></textarea>

                </div>


                <!-- Submit -->

                <button
                    type="submit"
                    class="submit-button"
                >
                    Submit Application
                </button>


            </form>

        </div>

    </section>


</main>


<?php include "includes/footer.php"; ?>

