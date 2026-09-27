<?php

if (!isset($_SESSION["user_id"]))
{
    header("Location: /homegenie-website/auth/login.php");
    exit;
}

$pageTitle = $pageTitle ?? "Customer Dashboard";

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
        href="/homegenie-website/css/customer/layout.css"
    >

    <?php if (isset($pageCss) && $pageCss != "") { ?>

    <link
        rel="stylesheet"
        href="/homegenie-website/css/customer/<?php echo $pageCss; ?>"
    >

    <?php } ?>

</head>

<body>

<div class="customer-layout">


    <!-- Sidebar -->

    <aside class="sidebar">


        <div class="sidebar-brand">

            <h1>
                HomeGenie
            </h1>

            <p>
                Customer Panel
            </p>

        </div>


        <nav class="sidebar-nav">


            <a
                href="/homegenie-website/customer/dashboard.php"
                class="<?php if ($currentPage == "dashboard.php") echo "active"; ?>"
            >
                Dashboard
            </a>


            <a
                href="/homegenie-website/customer/services.php"
                class="<?php if ($currentPage == "services.php") echo "active"; ?>"
            >
                Services
            </a>


            <a
                href="/homegenie-website/customer/my-bookings.php"
                class="<?php if ($currentPage == "my-bookings.php") echo "active"; ?>"
            >
                My Bookings
            </a>


            <a
                href="/homegenie-website/customer/profile.php"
                class="<?php if ($currentPage == "profile.php") echo "active"; ?>"
            >
                My Profile
            </a>


            <a
                href="/homegenie-website/customer/reviews.php"
                class="<?php if ($currentPage == "reviews.php") echo "active"; ?>"
            >
                Reviews
            </a>


        </nav>


        <!-- Sidebar Bottom -->

        <div class="sidebar-bottom">

            <a
                href="/homegenie-website/auth/logout.php"
                class="logout"
            >
                Logout
            </a>

        </div>


    </aside>


    <!-- Main Area -->

    <main class="main-area">


        <!-- Top Header -->

        <header class="top-header">


            <div class="header-title">

                <h2>
                    <?php echo htmlspecialchars($pageTitle); ?>
                </h2>

            </div>


            <div class="header-user">

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $_SESSION["user_name"]
                    );
                    ?>
                </strong>

                <span>
                    Customer
                </span>

            </div>


        </header>


        <!-- Page Content -->

        <div class="dashboard">

            <?php

            if (isset($pageContent))
            {
                echo $pageContent;
            }

            ?>

        </div>


    </main>


</div>

</body>

</html>