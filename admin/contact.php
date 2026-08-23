

<?php
include '../config/db.php';

$contactResult = $conn->query("SELECT * FROM contact");
?>
<?php

session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: ../auth/login.html");
    exit;
}

include "../config/db.php";

$adminName = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";

$bookingResult = $conn->query("SELECT * FROM bookings");

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contacts - HomeGenie Admin</title>

    <!-- Main Website CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Admin Panel CSS -->
    <link rel="stylesheet" href="../css/admin-sidebar.css">

</head>

<body>

<div class="admin-container">


    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <aside class="sidebar">

        <div class="sidebar-brand">

            <h2>HomeGenie</h2>

            <span>Admin Panel</span>

        </div>


        <nav>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="users.php">
                Manage Users
            </a>

            <a href="providers.php">
                Manage Providers
            </a>

            <a href="services.php">
                Manage Services
            </a>

            <a href="bookings.php">
                Manage Bookings
            </a>

            <a href="reviews.php">
                Manage Reviews
            </a>

            <a href="contact.php" class="active">
                Manage Contacts
            </a>

        </nav>


        <!-- Logout -->

        <div class="logout-button">

            <a href="../auth/logout.php">
                Logout
            </a>

        </div>

    </aside>



    <!-- =========================================================
         MAIN ADMIN AREA
    ========================================================== -->

    <main class="admin-main">


        <!-- =====================================================
             TOP NAVBAR
        ====================================================== -->

        <header class="admin-navbar">


            <!-- Page Title -->

            <div class="navbar-left">

                <h1>Contacts</h1>

            </div>



            <!-- Admin Profile -->

            <div class="admin-profile">


                <!-- First Letter Avatar -->

                <div class="admin-avatar">

                    <?php
                    echo strtoupper(
                        substr($adminName, 0, 1)
                    );
                    ?>

                </div>


                <!-- Admin Information -->

                <div class="admin-info">

                    <strong>

                        <?php
                        echo htmlspecialchars($adminName);
                        ?>

                    </strong>


                    <span>

                        <?php
                        echo htmlspecialchars($adminEmail);
                        ?>

                    </span>

                </div>

            </div>

        </header>



        <!-- =====================================================
             PAGE CONTENT
        ====================================================== -->

        <section class="admin-content">


            <h2>Manage Contact Messages</h2>



            <!-- =================================================
                 CONTACT TABLE
            ================================================== -->

            <div class="table-container">

                <table>


                    <!-- TABLE HEADER -->

                    <thead>

                        <tr>

                            <th>Contact ID</th>

                            <th>Full Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Subject</th>

                            <th>Message</th>

                            <th>Message Status</th>

                            <th>Created At</th>

                        </tr>

                    </thead>



                    <!-- TABLE DATA -->

                    <tbody>


                    <?php

                    if (
                        $contactResult &&
                        $contactResult->num_rows > 0
                    ):

                        while (
                            $contact = $contactResult->fetch_assoc()
                        ):

                    ?>

                        <tr>


                            <!-- Contact ID -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["contact_id"]
                                );
                                ?>

                            </td>



                            <!-- Full Name -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["full_name"]
                                );
                                ?>

                            </td>



                            <!-- Email -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["email"]
                                );
                                ?>

                            </td>



                            <!-- Phone -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["phone"]
                                );
                                ?>

                            </td>



                            <!-- Subject -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["subject"]
                                );
                                ?>

                            </td>



                            <!-- Message -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["message"]
                                );
                                ?>

                            </td>



                            <!-- Message Status -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["message_status"]
                                );
                                ?>

                            </td>



                            <!-- Created At -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $contact["created_at"]
                                );
                                ?>

                            </td>


                        </tr>


                    <?php

                        endwhile;

                    else:

                    ?>

                        <tr>

                            <td colspan="8">

                                No contact messages found.

                            </td>

                        </tr>

                    <?php

                    endif;

                    ?>


                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>

</html>