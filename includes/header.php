<?php

$pageTitle = $pageTitle ?? "HomeGenie";
$pageCss = $pageCss ?? "";

$currentPage = basename($_SERVER['PHP_SELF']);

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
        <?php echo htmlspecialchars($pageTitle); ?>
    </title>

    <link
        rel="stylesheet"
        href="/homegenie-website/css/style.css"
    >

    <?php if ($pageCss !== ""): ?>

        <link
            rel="stylesheet"
            href="/homegenie-website/css/pages/<?php echo htmlspecialchars($pageCss); ?>"
        >

    <?php endif; ?>

    <style>

        .links a.active{
            color: #0F766E !important;
            font-weight: 700 !important;
            background-color: #F0FDFA !important;
            border-radius: 6px !important;
        }

        .links a.active::after{
            display: none !important;
        }

        .links a.active:hover{
            color: #115E59 !important;
        }

    </style>

</head>

<body>

<div class="container">

    <header>

        <div class="navContainer">

            <div class="logo">

                <a href="/homegenie-website/index.php">

                    <img
                        src="/homegenie-website/assets/logo.png"
                        alt="HomeGenie Logo"
                    >

                </a>

            </div>

            <button
                class="menuToggle"
                type="button"
            >
                ☰
            </button>

            <div class="links">

                <a
                    href="/homegenie-website/index.php"
                    class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>"
                >
                    Home
                </a>

                <a
                    href="/homegenie-website/about.php"
                    class="<?php echo $currentPage === 'about.php' ? 'active' : ''; ?>"
                >
                    About
                </a>

                <a
                    href="/homegenie-website/services.php"
                    class="<?php echo $currentPage === 'services.php' ? 'active' : ''; ?>"
                >
                    Services
                </a>

                <a
                    href="/homegenie-website/provider-register.php"
                    class="<?php echo $currentPage === 'provider-register.php' ? 'active' : ''; ?>"
                >
                    Become a Provider
                </a>

                <a
                    href="/homegenie-website/contact.php"
                    class="<?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>"
                >
                    Contact Us
                </a>

                <a href="/homegenie-website/auth/login.php">
                    <button type="button">
                        Login
                    </button>
                </a>

                <a href="/homegenie-website/register.php">
                    <button type="button">
                        Register
                    </button>
                </a>

            </div>

        </div>

    </header>

