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

<title><?php echo htmlspecialchars($pageTitle); ?> - HomeGenie</title>

<link rel="stylesheet" href="<?php echo $assetPath; ?>css/admin/layout.css">

<?php if ($pageCss != "") { ?>
<link rel="stylesheet" href="<?php echo $assetPath; ?>css/admin/pages/<?php echo $pageCss; ?>">
<?php } ?>

</head>

<body>

<nav class="top-navbar">

    <a href="<?php echo $adminPath; ?>dashboard.php" class="navbar-brand">
        <span>HomeGenie</span>
    </a>

    <div class="navbar-right">

        <div class="admin-info">
            <i class="fa-solid fa-user"></i>

            <span>
                Hello <strong><?php echo htmlspecialchars($adminName); ?></strong>
            </span>
        </div>

        <a href="<?php echo $assetPath; ?>auth/logout.php" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

    <button type="button"
            class="mobile-menu-button"
            onclick="showMobileMenu()">
        ☰
    </button>

</nav>


<div class="admin-container">

    <aside class="sidebar">

        <div class="sidebar-title">
            Admin Menu
        </div>

        <ul class="sidebar-menu">

            <li>
                <a href="<?php echo $adminPath; ?>dashboard.php"
                   class="sidebar-link <?php echo ($currentPage == "dashboard.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/categories/categories.php"
                   class="sidebar-link <?php echo ($currentPage == "categories.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-list"></i>
                    <span>Categories</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/services/services.php"
                   class="sidebar-link <?php echo ($currentPage == "services.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <span>Services</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
                   class="sidebar-link <?php echo ($currentPage == "service-providers.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Service Providers</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/users/users.php"
                   class="sidebar-link <?php echo ($currentPage == "users.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
                   class="sidebar-link <?php echo ($currentPage == "bookings.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/reviews/reviews.php"
                   class="sidebar-link <?php echo ($currentPage == "reviews.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-star"></i>
                    <span>Reviews</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $adminPath; ?>pages/contacts/contacts.php"
                   class="sidebar-link <?php echo ($currentPage == "contacts.php") ? "active" : ""; ?>">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Contacts</span>
                </a>
            </li>

        </ul>

        <div class="logout-area">

            <a href="<?php echo $assetPath; ?>auth/logout.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>

        </div>

    </aside>


    <div class="mobile-menu" id="mobileMenu">

        <div class="mobile-menu-title">
            Admin Menu
        </div>

        <div class="mobile-menu-links">

            <a href="<?php echo $adminPath; ?>dashboard.php"
               class="<?php echo ($currentPage == "dashboard.php") ? "mobile-active" : ""; ?>">
                Dashboard
            </a>

            <a href="<?php echo $adminPath; ?>pages/categories/categories.php"
               class="<?php echo ($currentPage == "categories.php") ? "mobile-active" : ""; ?>">
                Categories
            </a>

            <a href="<?php echo $adminPath; ?>pages/services/services.php"
               class="<?php echo ($currentPage == "services.php") ? "mobile-active" : ""; ?>">
                Services
            </a>

            <a href="<?php echo $adminPath; ?>pages/service-providers/service-providers.php"
               class="<?php echo ($currentPage == "service-providers.php") ? "mobile-active" : ""; ?>">
                Service Providers
            </a>

            <a href="<?php echo $adminPath; ?>pages/users/users.php"
               class="<?php echo ($currentPage == "users.php") ? "mobile-active" : ""; ?>">
                Users
            </a>

            <a href="<?php echo $adminPath; ?>pages/bookings/bookings.php"
               class="<?php echo ($currentPage == "bookings.php") ? "mobile-active" : ""; ?>">
                Bookings
            </a>

            <a href="<?php echo $adminPath; ?>pages/reviews/reviews.php"
               class="<?php echo ($currentPage == "reviews.php") ? "mobile-active" : ""; ?>">
                Reviews
            </a>

            <a href="<?php echo $adminPath; ?>pages/contacts/contacts.php"
               class="<?php echo ($currentPage == "contacts.php") ? "mobile-active" : ""; ?>">
                Contacts
            </a>

        </div>

        <a href="<?php echo $assetPath; ?>auth/logout.php" class="mobile-logout">
            Logout
        </a>

    </div>


    <main class="main-content">

        <?php echo $pageContent ?? ""; ?>

    </main>

</div>


<script>

function showMobileMenu()
{
    var menu = document.getElementById("mobileMenu");

    if (menu.style.display == "block")
    {
        menu.style.display = "none";
    }
    else
    {
        menu.style.display = "block";
    }
}

</script>

</body>
</html>