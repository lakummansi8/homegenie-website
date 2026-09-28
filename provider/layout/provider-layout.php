<?php

if (!isset($_SESSION["provider_logged_in"]))
{
    header("Location: /homegenie-website/auth/login.php");
    exit;
}

$pageTitle = $pageTitle ?? "Provider Dashboard";
$pageCss = $pageCss ?? "";

$currentPage = basename($_SERVER["PHP_SELF"]);

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
        rel="stylesheet"
        href="/homegenie-website/css/provider/layout.css"
    >

    <?php if ($pageCss != "") { ?>

        <link
            rel="stylesheet"
            href="/homegenie-website/css/provider/<?php echo $pageCss; ?>"
        >

    <?php } ?>

</head>

<body>

<div class="provider-layout">


    <aside class="provider-sidebar">


        <div class="provider-logo">

            <h2>
                HomeGenie
            </h2>

            <span>
                Provider Panel
            </span>

        </div>


        <nav class="provider-nav">


            <a
                href="dashboard.php"
                class="provider-nav-link <?php if ($currentPage == "dashboard.php") echo "active"; ?>"
            >
                Dashboard
            </a>


            <a
                href="myprofile.php"
                class="provider-nav-link <?php if ($currentPage == "myprofile.php") echo "active"; ?>"
            >
                My Profile
            </a>


            <a
                href="services.php"
                class="provider-nav-link <?php if ($currentPage == "services.php") echo "active"; ?>"
            >
                My Services
            </a>


            <a
                href="bookings.php"
                class="provider-nav-link <?php if ($currentPage == "bookings.php") echo "active"; ?>"
            >
                Bookings
            </a>


            <a
                href="customers.php"
                class="provider-nav-link <?php if ($currentPage == "customers.php") echo "active"; ?>"
            >
                Customers
            </a>


            <a
                href="reviews.php"
                class="provider-nav-link <?php if ($currentPage == "reviews.php") echo "active"; ?>"
            >
                Reviews
            </a>


        </nav>


        <div class="provider-sidebar-bottom">

            <a
                href="../auth/logout.php"
                class="provider-logout"
            >
                Logout
            </a>

        </div>


    </aside>


    <main class="provider-main">


        <header class="provider-topbar">


            <div class="topbar-title">

                <h1>
                    <?php echo htmlspecialchars($pageTitle); ?>
                </h1>

            </div>


            <div class="provider-user">

                <div class="provider-user-info">

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION["provider_name"]
                        );
                        ?>
                    </strong>

                    <span>
                        Provider
                    </span>

                </div>

            </div>


        </header>


        <section class="provider-content">