<?php

if (!isset($_SESSION["user_id"])) {
    header("Location: /homegenie-website/auth/login.php");
    exit;
}

$pageTitle = $pageTitle ?? "Customer Dashboard";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($pageTitle); ?> - HomeGenie
    </title>

    <link rel="stylesheet" href="/homegenie-website/customer/customer.css">

</head>

<body>

<div class="customer-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">

            <h1>HomeGenie</h1>

            <p>Customer Panel</p>

        </div>


        <nav class="sidebar-nav">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="services.php">
                Services
            </a>

            <a href="my-bookings.php">
                My Bookings
            </a>

            <a href="profile.php">
                My Profile
            </a>

            <a href="reviews.php">
                Reviews
            </a>

        </nav>


        <a href="../auth/logout.php" class="logout">
            Logout
        </a>

    </aside>


    <main class="main-area">

        <header class="top-header">

            <h2>
                <?php echo htmlspecialchars($pageTitle); ?>
            </h2>

            <div class="header-user">

                <strong>
                    <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                </strong>

                <span>Customer</span>

            </div>

        </header>


        <div class="dashboard">