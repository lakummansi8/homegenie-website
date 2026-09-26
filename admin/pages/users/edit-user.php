<?php

$pageTitle = "Edit Customer";
$pageCss = "users.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$userId = $_GET["id"];


if ($userId == "")
{
    header("Location: users.php?error=invalid_user");
    exit;
}


$sql = "select * from users where user_id = $userId";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) == 0)
{
    header("Location: users.php?error=user_not_found");
    exit;
}


$user = mysqli_fetch_array($result);


$fullName = $user["full_name"];
$email = $user["email"];
$phone = $user["phone"];
$address = $user["address"];
$city = $user["city"];
$accountStatus = $user["account_status"];

$error = "";


if (isset($_POST["submit"]))
{

    $fullName = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
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

        $sql = "select * from users
                where email = '$email'
                and user_id != $userId";

        $result = mysqli_query($conn, $sql);


        if (mysqli_num_rows($result) > 0)
        {
            $error = "Another customer is already using this email address.";
        }

    }


    if ($error == "")
    {

        $sql = "update users set
                full_name = '$fullName',
                email = '$email',
                phone = '$phone',
                address = '$address',
                city = '$city',
                account_status = '$accountStatus'
                where user_id = $userId";


        $result = mysqli_query($conn, $sql);


        if ($result)
        {
            header("Location: users.php?success=user_updated");
            exit;
        }
        else
        {
            $error = "Unable to update customer. Please try again.";
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
                Edit Customer
            </h2>

            <p>
                Update the information and account status of this customer.
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

            <span class="form-customer-number">
                Customer #<?php echo $userId; ?>
            </span>

        </div>


        <div class="users-form-body">


            <div class="users-form-section">

                <h4>
                    Basic Information
                </h4>

                <p>
                    Update the customer's basic account information.
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


                </div>


                <div class="users-form-row">


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


                    <div class="users-form-group users-empty-form-space">

                    </div>


                </div>


                <div class="users-form-section users-address-section">

                    <h4>
                        Address
                    </h4>

                    <p>
                        Update the customer's complete address.
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
                        Save Changes
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