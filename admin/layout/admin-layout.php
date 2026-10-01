<?php

require_once __DIR__ . "/../../auth/auth-check.php";

$adminName = $_SESSION["admin_name"] ?? "Admin";

$currentPage = basename($_SERVER["PHP_SELF"]);

$adminPath = $adminPath ?? "";
$assetPath = $assetPath ?? "";

$pageTitle = $pageTitle ?? "Admin Panel";
$pageCss = $pageCss ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($pageTitle); ?> - HomeGenie
    </title>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- Admin Layout CSS -->
    <link
        rel="stylesheet"
        href="<?php echo $assetPath; ?>css/admin/layout.css"
    >

    <?php if ($pageCss != "") { ?>

        <link
            rel="stylesheet"
            href="<?php echo $assetPath; ?>css/admin/pages/<?php echo $pageCss; ?>"
        >

    <?php } ?>

</head>

<body>

    <!-- TOP NAVBAR -->

    <nav class="top-navbar">

        <div class="navbar-left">

            <a
                href="<?php echo $adminPath; ?>dashboard.php"
                class="navbar-brand"
            >

                <i class="fa-solid fa-house-gear"></i>

                <span>HomeGenie Admin</span>

            </a>

        </div>

        <div class="navbar-right">

            <div class="admin-info">

                <i class="fa-solid fa-circle-user"></i>

                <span>
                    Hello,
                    <strong>
                        <?php echo htmlspecialchars($adminName); ?>
                    </strong>
                </span>

            </div>

        </div>

        <button
            type="button"
            class="mobile-menu-button"
            onclick="showMobileMenu()"
        >

            <i class="fa-solid fa-bars"></i>

        </button>

    </nav>


    <!-- PAGE AREA -->

    <div class="admin-container">


        <!-- SIDEBAR -->

        <aside class="sidebar">

            <div class="sidebar-title">
                Main Navigation
            </div>


            <ul class="sidebar-menu">


                <!-- Dashboard -->

                <li>

                    <a
                        href="<?php echo $adminPath; ?>dashboard.php"
                        class="sidebar-link <?php echo ($currentPage == "dashboard.php") ? "active" : ""; ?>"
                    >

                        <i class="fa-solid fa-gauge"></i>

                        <span>Dashboard</span>

                    </a>

                </li>


                <!-- Categories -->

                <li>

                    <a
                        href="<?php echo $adminPath; ?>pages/categories/categories.php"
                        class="sidebar-link <?php echo ($currentPage == "categories.php") ? "active" : ""; ?>"
                    >

                        <i class="fa-solid fa-tags"></i>

                        <span>Categories</span>

                    </a>

                </li>


                <!-- Services -->

                <li>

                    <a
                        href="<?php echo $adminPath; ?>pages/services/services.php"
                        class="sidebar-link <?php echo ($currentPage == "services.php") ? "active" : ""; ?>"
                    >

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                        <span>Services</span>

                    </a>

                </li>


                <!-- Providers -->

                <li>

                    <a
                        href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
                        class="sidebar-link <?php echo ($currentPage == "service-providers.php") ? "active" : ""; ?>"
                    >

                        <i class="fa-solid fa-user-tie"></i>

                        <span>Providers</span>

                    </a>

                </li>


                <!-- Customers -->

                <li>

                    <a
                        href="<?php echo $adminPath; ?>pages/users/users.php"
                        class="sidebar-link <?php echo ($currentPage == "users.php") ? "active" : ""; ?>"
                    >

                        <i class="fa-solid fa-users"></i>

                        <span>Customers</span>

                    </a>

                </li>


                <!-- Bookings -->

                <li>

                    <a
                        href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
                        class="sidebar-link <?php echo ($currentPage == "bookings.php") ? "active" : ""; ?>"
                    >

                        <i class="fa-solid fa-calendar-check"></i>

                        <span>Bookings</span>

                    </a>

                </li>

                 <li>

                    <a
                        href="<?php echo $adminPath; ?>pages/reviews/reviews.php"
                        class="sidebar-link <?php echo ($currentPage == "reviews.php") ? "active" : ""; ?>"
                    >

                       <i class="fa-solid fa-star"></i>

                        <span>Reviews</span>

                    </a>
                     <a
                        href="<?php echo $adminPath; ?>pages/contacts/contacts.php"
                        class="sidebar-link <?php echo ($currentPage == "contacts.php") ? "active" : ""; ?>"
                    >

                       <i class="fa-solid fa-envelope"></i>

                        <span>Contacts</span>

                    </a>

                </li>

            </ul>


            <!-- LOGOUT -->

            <div class="logout-area">

                <a
                    href="<?php echo $assetPath; ?>auth/logout.php"
                    class="logout-link"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <span>Logout</span>

                </a>

            </div>

        </aside>


        <!-- MOBILE MENU -->

        <div
            class="mobile-menu"
            id="mobileMenu"
        >

            <div class="mobile-menu-title">
                Menu
            </div>


            <div class="mobile-menu-links">

                <a
                    href="<?php echo $adminPath; ?>dashboard.php"
                    class="<?php echo ($currentPage == "dashboard.php") ? "mobile-active" : ""; ?>"
                >
                    Dashboard
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/categories/categories.php"
                    class="<?php echo ($currentPage == "categories.php") ? "mobile-active" : ""; ?>"
                >
                    Categories
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/services/services.php"
                    class="<?php echo ($currentPage == "services.php") ? "mobile-active" : ""; ?>"
                >
                    Services
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
                    class="<?php echo ($currentPage == "service-providers.php") ? "mobile-active" : ""; ?>"
                >
                    Providers
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/users/users.php"
                    class="<?php echo ($currentPage == "users.php") ? "mobile-active" : ""; ?>"
                >
                    Customers
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
                    class="<?php echo ($currentPage == "bookings.php") ? "mobile-active" : ""; ?>"
                >
                    Bookings
                </a>
                <a
                        href="<?php echo $adminPath; ?>pages/reviews/reviews.php"
                        class="sidebar-link <?php echo ($currentPage == "reviews.php") ? "active" : ""; ?>"
                    >

                       <i class="fa-solid fa-star"></i>

                        <span>Reviews</span>

                    </a>

            </div>


            <a
                href="<?php echo $assetPath; ?>auth/logout.php"
                class="mobile-logout"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </div>


        <!-- MAIN CONTENT -->

        <main class="main-content">

            <?php echo $pageContent ?? ""; ?>

        </main>

    </div>


    <script>

        function showMobileMenu() {

            var menu = document.getElementById("mobileMenu");

            if (menu.style.display == "block") {

                menu.style.display = "none";

            } else {

                menu.style.display = "block";

            }

        }

    </script>

</body>

</html>