<?php

$pageTitle = "Customers";
$pageCss = "users.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$sql = "select * from users order by user_id desc";

$result = mysqli_query($conn, $sql);


if (!$result)
{
    die("Customer query failed.");
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
                Customers
            </h2>

            <p>
                View and manage HomeGenie customer accounts.
            </p>

        </div>


        <div class="users-header-actions">

            <a
                href="add-user.php"
                class="users-primary-button"
            >

                <span class="users-button-icon">
                    +
                </span>

                Add Customer

            </a>

        </div>

    </div>


    <?php if (isset($_GET["success"])) { ?>

        <div class="users-alert users-alert-success">

            <?php

            if ($_GET["success"] == "user_added")
            {
                echo "Customer added successfully.";
            }
            elseif ($_GET["success"] == "user_updated")
            {
                echo "Customer updated successfully.";
            }
            elseif ($_GET["success"] == "user_deleted")
            {
                echo "Customer deleted successfully.";
            }

            ?>

        </div>

    <?php } ?>


    <?php if (isset($_GET["error"])) { ?>

        <div class="users-alert users-alert-error">

            <?php

            if ($_GET["error"] == "invalid_user")
            {
                echo "Invalid customer.";
            }
            elseif ($_GET["error"] == "user_not_found")
            {
                echo "Customer not found.";
            }
            elseif ($_GET["error"] == "delete_failed")
            {
                echo "Unable to delete customer.";
            }
            else
            {
                echo "Something went wrong.";
            }

            ?>

        </div>

    <?php } ?>


    <div class="users-card">


        <div class="users-card-header">

            <div>

                <span class="users-card-eyebrow">
                    Customer Accounts
                </span>

                <h3>
                    All Customers
                </h3>

            </div>


            <span class="users-count">

                <?php echo mysqli_num_rows($result); ?>

                Customers

            </span>

        </div>


        <?php if (mysqli_num_rows($result) == 0) { ?>


            <div class="users-empty-state">


                <div class="users-empty-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                        />

                        <circle
                            cx="9"
                            cy="7"
                            r="4"
                        />

                        <path
                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                        />

                        <path
                            d="M16 3.13a4 4 0 0 1 0 7.75"
                        />

                    </svg>

                </div>


                <h4>
                    No Customers Found
                </h4>


                <p>
                    There are currently no customer accounts in the system.
                </p>


                <a
                    href="add-user.php"
                    class="users-empty-button"
                >
                    Add First Customer
                </a>


            </div>


        <?php } else { ?>


            <div class="users-table-wrapper">


                <table class="users-table">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Contact
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Registered
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        $srno = 1;

                        while ($user = mysqli_fetch_array($result))
                        {

                        ?>


                            <tr>


                                <td>

                                    <span class="user-id">

                                        <?php echo $srno; ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="customer-info">


                                        <div class="customer-avatar">

                                            <?php

                                            echo strtoupper(
                                                substr(
                                                    $user["full_name"],
                                                    0,
                                                    1
                                                )
                                            );

                                            ?>

                                        </div>


                                        <div class="customer-name-wrapper">

                                            <strong>

                                                <?php

                                                echo htmlspecialchars(
                                                    $user["full_name"]
                                                );

                                                ?>

                                            </strong>


                                            <span>
                                                Customer
                                            </span>

                                        </div>


                                    </div>

                                </td>


                                <td>

                                    <div class="customer-contact">


                                        <?php if ($user["email"] != "") { ?>

                                            <a
                                                href="mailto:<?php echo htmlspecialchars($user["email"]); ?>"
                                                class="customer-email"
                                            >

                                                <?php

                                                echo htmlspecialchars(
                                                    $user["email"]
                                                );

                                                ?>

                                            </a>

                                        <?php } else { ?>

                                            <span class="not-available">
                                                No email
                                            </span>

                                        <?php } ?>


                                        <?php if ($user["phone"] != "") { ?>

                                            <span class="customer-phone">

                                                <?php

                                                echo htmlspecialchars(
                                                    $user["phone"]
                                                );

                                                ?>

                                            </span>

                                        <?php } else { ?>

                                            <span class="not-available">
                                                No phone
                                            </span>

                                        <?php } ?>


                                    </div>

                                </td>


                                <td>

                                    <div class="customer-location">


                                        <?php if ($user["city"] != "") { ?>

                                            <strong>

                                                <?php

                                                echo htmlspecialchars(
                                                    $user["city"]
                                                );

                                                ?>

                                            </strong>

                                        <?php } else { ?>

                                            <strong>
                                                No city
                                            </strong>

                                        <?php } ?>


                                        <?php if ($user["address"] != "") { ?>

                                            <span>

                                                <?php

                                                echo htmlspecialchars(
                                                    $user["address"]
                                                );

                                                ?>

                                            </span>

                                        <?php } else { ?>

                                            <span>
                                                No address
                                            </span>

                                        <?php } ?>


                                    </div>

                                </td>


                                <td>

                                    <span class="registered-date">

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $user["created_at"]
                                            )
                                        );

                                        ?>

                                    </span>

                                </td>


                                <td>


                                    <?php if ($user["account_status"] == "Active") { ?>


                                        <span class="user-status active">

                                            <span class="status-dot"></span>

                                            Active

                                        </span>


                                    <?php } elseif ($user["account_status"] == "Blocked") { ?>


                                        <span class="user-status blocked">

                                            <span class="status-dot"></span>

                                            Blocked

                                        </span>


                                    <?php } else { ?>


                                        <span class="user-status pending">

                                            <span class="status-dot"></span>

                                            <?php

                                            echo htmlspecialchars(
                                                $user["account_status"]
                                            );

                                            ?>

                                        </span>


                                    <?php } ?>


                                </td>


                                <td>


                                    <div class="user-actions">


                                        <a
                                            href="edit-user.php?id=<?php echo $user["user_id"]; ?>"
                                            class="user-action edit-action"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="delete-user.php?id=<?php echo $user["user_id"]; ?>"
                                            class="user-action delete-action"
                                            onclick="return confirm('Are you sure you want to delete this customer?');"
                                        >
                                            Delete
                                        </a>


                                    </div>


                                </td>


                            </tr>


                        <?php

                            $srno++;

                        }

                        ?>


                    </tbody>


                </table>


            </div>


        <?php } ?>


    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>