<?php

$pageTitle = "Add Customer";
$pageCss = "users.css";

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


if (isset($_POST["submit"]))
{

    $fullName = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $accountStatus = $_POST["account_status"];


    if ($fullName == "")
    {
        $error = "Customer name is required.";
    }
    elseif ($email == "")
    {
        $error = "Email address is required.";
    }
    elseif ($phone == "")
    {
        $error = "Phone number is required.";
    }
    elseif ($password == "")
    {
        $error = "Password is required.";
    }
    elseif (strlen($password) < 6)
    {
        $error = "Password must be at least 6 characters.";
    }
    elseif ($address == "")
    {
        $error = "Address is required.";
    }
    elseif ($city == "")
    {
        $error = "City is required.";
    }


    if ($error == "")
    {

        $sql = "select * from users where email = '$email'";

        $result = mysqli_query($conn, $sql);


        if (mysqli_num_rows($result) > 0)
        {
            $error = "A customer with this email address already exists.";
        }

    }


    if ($error == "")
    {

        $password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $sql = "insert into users
        (
            full_name,
            email,
            phone,
            password,
            address,
            city,
            account_status
        )
        values
        (
            '$fullName',
            '$email',
            '$phone',
            '$password',
            '$address',
            '$city',
            '$accountStatus'
        )";


        $result = mysqli_query($conn, $sql);


        if ($result)
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


<div class="users-page">


    <div class="users-header">

        <div class="users-heading">

            <span class="users-eyebrow">
                Customer Management
            </span>

            <h2>
                Add Customer
            </h2>

            <p>
                Create a new HomeGenie customer account.
            </p>

        </div>


        <div class="users-header-actions">

            <a
                href="users.php"
                class="users-secondary-button"
            >
                &larr; Back to Customers
            </a>

        </div>

    </div>


    <?php if ($error != "") { ?>

        <div class="users-alert users-alert-error">

            <strong>
                Please fix the following:
            </strong>

            <ul>

                <li>
                    <?php echo htmlspecialchars($error); ?>
                </li>

            </ul>

        </div>

    <?php } ?>


    <div class="users-form-card">


        <div class="users-form-header">

            <strong>
                Account Details
            </strong>

        </div>


        <div class="users-form-body">


            <div class="users-form-section">

                <h4>
                    Basic Information
                </h4>

                <p>
                    Enter the customer's basic account information.
                </p>

            </div>


            <form
                method="POST"
                autocomplete="off"
            >


                <div class="users-form-row">


                    <div class="users-form-group">

                        <label>
                            Full Name <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="full_name"
                            value="<?php echo htmlspecialchars($fullName); ?>"
                            required
                            maxlength="100"
                        >

                    </div>


                    <div class="users-form-group">

                        <label>
                            Email Address <span>*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                            maxlength="150"
                        >

                    </div>


                </div>


                <div class="users-form-row">


                    <div class="users-form-group">

                        <label>
                            Phone Number <span>*</span>
                        </label>

                        <input
                            type="tel"
                            name="phone"
                            value="<?php echo htmlspecialchars($phone); ?>"
                            required
                            maxlength="20"
                        >

                    </div>


                    <div class="users-form-group">

                        <label>
                            Password <span>*</span>
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            minlength="6"
                            autocomplete="new-password"
                        >

                        <small>
                            Minimum 6 characters.
                        </small>

                    </div>


                </div>


                <div class="users-form-row">


                    <div class="users-form-group">

                        <label>
                            City <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="city"
                            value="<?php echo htmlspecialchars($city); ?>"
                            required
                            maxlength="100"
                        >

                    </div>


                    <div class="users-form-group">

                        <label>
                            Account Status <span>*</span>
                        </label>

                        <select
                            name="account_status"
                            required
                        >

                            <option
                                value="Active"
                                <?php
                                if ($accountStatus == "Active")
                                {
                                    echo "selected";
                                }
                                ?>
                            >
                                Active
                            </option>

                            <option
                                value="Blocked"
                                <?php
                                if ($accountStatus == "Blocked")
                                {
                                    echo "selected";
                                }
                                ?>
                            >
                                Blocked
                            </option>

                        </select>

                    </div>


                </div>


                <div class="users-form-section users-address-section">

                    <h4>
                        Address
                    </h4>

                    <p>
                        Enter the customer's complete address.
                    </p>

                </div>


                <div class="users-form-group users-address-group">

                    <label>
                        Full Address <span>*</span>
                    </label>

                    <textarea
                        name="address"
                        rows="4"
                        required
                        maxlength="500"
                    ><?php echo htmlspecialchars($address); ?></textarea>

                </div>


                <div class="users-form-actions">


                    <a
                        href="users.php"
                        class="users-cancel-button"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        name="submit"
                        class="users-submit-button"
                    >
                        Add Customer
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