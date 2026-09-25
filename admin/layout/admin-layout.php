<?php

require_once __DIR__ . "/../../auth/auth-check.php";

$adminName = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";

$currentPage = basename($_SERVER["PHP_SELF"]);

$adminPath = $adminPath ?? "";
$assetPath = $assetPath ?? "";

$pageTitle = $pageTitle ?? "Admin Panel";
$pageCss = $pageCss ?? "";


function isActivePage($pageName, $currentPage)
{
    return $pageName === $currentPage ? "active" : "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($pageTitle); ?> - HomeGenie
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <link
        rel="stylesheet"
        href="<?php echo $assetPath; ?>css/admin/layout.css"
    >


    <?php if ($pageCss != ""): ?>

        <link
            rel="stylesheet"
            href="<?php echo $assetPath; ?>css/admin/pages/<?php echo htmlspecialchars($pageCss); ?>"
        >

    <?php endif; ?>

</head>


<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">

    <div class="container-fluid px-4">

        <a
            class="navbar-brand text-white fw-bold d-flex align-items-center"
            href="<?php echo $adminPath; ?>dashboard.php"
        >

            <i class="bi bi-house-gear-fill text-primary me-2 fs-4"></i>

            HomeGenie Admin

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#topNavBar"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse justify-content-end"
            id="topNavBar"
        >

            <div class="d-flex align-items-center text-white">

                <div class="d-flex align-items-center me-3">

                    <i class="bi bi-person-circle fs-5 me-2"></i>

                    <span>
                        Hello,
                        <strong>
                            <?php echo htmlspecialchars($adminName); ?>
                        </strong>
                    </span>

                </div>

            </div>

        </div>

    </div>

</nav>


<div class="container-fluid">

    <div class="row">


        <!-- SIDEBAR -->

        <div class="col-md-3 col-lg-2 bg-white sidebar shadow-sm d-none d-md-flex flex-column px-0 py-3">

            <h6 class="sidebar-heading px-3 mt-2 mb-2 text-muted text-uppercase fw-bold">
                Main Navigation
            </h6>


            <ul class="nav flex-column mb-auto w-100">

                <li class="nav-item">

                    <a
                        href="<?php echo $adminPath; ?>dashboard.php"
                        class="nav-link <?php echo isActivePage("dashboard.php", $currentPage); ?>"
                    >

                        <i class="bi bi-speedometer2"></i>

                        Dashboard

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="<?php echo $adminPath; ?>pages/categories/categories.php"
                        class="nav-link <?php echo isActivePage("categories.php", $currentPage); ?>"
                    >

                        <i class="bi bi-tags"></i>

                        Categories

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="<?php echo $adminPath; ?>pages/services/services.php"
                        class="nav-link <?php echo isActivePage("services.php", $currentPage); ?>"
                    >

                        <i class="bi bi-tools"></i>

                        Services

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
                        class="nav-link <?php echo isActivePage("service-providers.php", $currentPage); ?>"
                    >

                        <i class="bi bi-person-badge"></i>

                        Providers

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="<?php echo $adminPath; ?>pages/users/users.php"
                        class="nav-link <?php echo isActivePage("users.php", $currentPage); ?>"
                    >

                        <i class="bi bi-people"></i>

                        Customers

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
                        class="nav-link <?php echo isActivePage("bookings.php", $currentPage); ?>"
                    >

                        <i class="bi bi-calendar-check"></i>

                        Bookings

                    </a>

                </li>

            </ul>


            <div class="mt-auto px-3">

                <a
                    href="<?php echo $assetPath; ?>auth/logout.php"
                    class="nav-link w-100"
                >

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </a>

            </div>

        </div>


        <!-- MOBILE MENU -->

        <div class="d-md-none bg-white">

            <h6>Menu</h6>


            <div class="d-flex flex-wrap gap-2 mb-3">

                <a
                    href="<?php echo $adminPath; ?>dashboard.php"
                    class="btn btn-sm <?php echo $currentPage === "dashboard.php" ? "btn-primary" : "btn-light border"; ?>"
                >
                    Dashboard
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/categories/categories.php"
                    class="btn btn-sm <?php echo $currentPage === "categories.php" ? "btn-primary" : "btn-light border"; ?>"
                >
                    Categories
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/services/services.php"
                    class="btn btn-sm <?php echo $currentPage === "services.php" ? "btn-primary" : "btn-light border"; ?>"
                >
                    Services
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
                    class="btn btn-sm <?php echo $currentPage === "service-providers.php" ? "btn-primary" : "btn-light border"; ?>"
                >
                    Providers
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/users/users.php"
                    class="btn btn-sm <?php echo $currentPage === "users.php" ? "btn-primary" : "btn-light border"; ?>"
                >
                    Customers
                </a>


                <a
                    href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
                    class="btn btn-sm <?php echo $currentPage === "bookings.php" ? "btn-primary" : "btn-light border"; ?>"
                >
                    Bookings
                </a>

            </div>


            <a
                href="<?php echo $assetPath; ?>auth/logout.php"
                class="btn btn-sm btn-outline-danger w-100"
            >

                <i class="bi bi-box-arrow-right me-1"></i>

                Logout

            </a>

        </div>


        <!-- MAIN CONTENT -->

        <div class="col-md-9 col-lg-10">

            <?php echo $pageContent ?? ""; ?>

        </div>


    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>