<?php

$pageTitle = $pageTitle ?? "HomeGenie";
$pageCss = $pageCss ?? "";

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

                <a href="/homegenie-website/index.php">
                    Home
                </a>

                <a href="/homegenie-website/about.php">
                    About
                </a>

                <a href="/homegenie-website/services.php">
                    Services
                </a>

                <a href="/homegenie-website/provider-register.php">
                    Become a Provider
                </a>

                <a href="/homegenie-website/contact.php">
                    Contact Us
                </a>

                <a href="/homegenie-website/auth/login.php">
                    <button type="button">
                        Login
                    </button>
                </a>

                <a href="/homegenie-website/customer/register.php">
                    <button type="button">
                        Register
                    </button>
                </a>

            </div>

        </div>

    </header>