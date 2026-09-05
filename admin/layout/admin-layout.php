<?php

require_once __DIR__ . "/../../auth/auth-check.php";

$adminName = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";

$currentPage = basename($_SERVER["PHP_SELF"]);
$adminPath = $adminPath ?? "";

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
        <?= htmlspecialchars($pageTitle ?? "Admin Panel") ?> - HomeGenie
    </title>

    <!-- Google Font -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- HomeGenie Core -->

<link
    rel="stylesheet"
    href="<?= $assetPath ?>css/core/variables.css"
>

<link
    rel="stylesheet"
    href="<?= $assetPath ?>css/core/reset.css"
>

<link
    rel="stylesheet"
    href="<?= $assetPath ?>css/core/typography.css"
>

<!-- Admin Layout -->

<link
    rel="stylesheet"
    href="<?= $assetPath ?>css/admin/layout.css"
>

<!-- Current Page CSS -->

<?php if (!empty($pageCss)): ?>

<link
    rel="stylesheet"
    href="<?= $assetPath ?>css/admin/pages/<?= htmlspecialchars($pageCss) ?>"
>

<?php endif; ?>
</head>

<body>

<div class="admin-container">

    <!-- ================================
         SIDEBAR
         ================================ -->

    <aside class="admin-sidebar">

        <div class="admin-sidebar-brand">

            <a href="../dashboard.php">

                <img
                   src="<?= $assetPath ?>assets/logo.png"
                    alt="HomeGenie Logo"
                >

                <span>HomeGenie</span>

            </a>

        </div>


        <nav class="admin-sidebar-nav">

            <div class="admin-nav-section">

                <span class="admin-nav-title">
                    Main
                </span>


                <a
                   href="<?= $adminPath ?>dashboard.php"
                    class="admin-nav-link <?= isActivePage("dashboard.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">▦</span>
                    <span>Dashboard</span>
                </a>


                <a
                    href="<?= $adminPath ?>pages/categories/categories.php"
                    class="admin-nav-link <?= isActivePage("categories.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">◫</span>
                    <span>Categories</span>
                </a>


                <a
                    href="<?= $adminPath ?>pages/services.php"
                    class="admin-nav-link <?= isActivePage("services.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">◇</span>
                    <span>Services</span>
                </a>


                <a
                    href="<?= $adminPath ?>pages/locations.php"
                    class="admin-nav-link <?= isActivePage("locations.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">⌖</span>
                    <span>Locations</span>
                </a>

            </div>


            <div class="admin-nav-section">

                <span class="admin-nav-title">
                    Management
                </span>


                <a
                    href="<?= $adminPath ?>pages/admin-users.php"
                    class="admin-nav-link <?= isActivePage("admin-users.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">♙</span>
                    <span>Admin Users</span>
                </a>


                <a
                    href="<?= $adminPath ?>pages/settings.php"
                    class="admin-nav-link <?= isActivePage("settings.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">⚙</span>
                    <span>Settings</span>
                </a>


                <a
                    href="<?= $adminPath ?>pages/audit-logs.php"
                    class="admin-nav-link <?= isActivePage("audit-logs.php", $currentPage) ?>"
                >
                    <span class="admin-nav-icon">☷</span>
                    <span>Audit Logs</span>
                </a>

            </div>

        </nav>


        <div class="admin-sidebar-footer">

            <a
                href="<?= $assetPath ?>auth/logout.php"
                class="admin-logout"
            >
                <span>↪</span>
                <span>Logout</span>
            </a>

        </div>

    </aside>


    <!-- ================================
         MAIN AREA
         ================================ -->

    <main class="admin-main">

        <!-- TOP NAVBAR -->

        <header class="admin-navbar">

            <div class="admin-navbar-left">

                <button
                    type="button"
                    class="admin-menu-toggle"
                    id="adminMenuToggle"
                    aria-label="Toggle navigation"
                >
                    ☰
                </button>

                <div>

                    <p class="admin-navbar-label">
                        HomeGenie Admin
                    </p>

                    <h1 class="admin-navbar-title">
                        <?= htmlspecialchars($pageTitle ?? "Admin Panel") ?>
                    </h1>

                </div>

            </div>


            <div class="admin-navbar-right">

                <div class="admin-profile">

                    <div class="admin-avatar">

                        <?= strtoupper(substr($adminName, 0, 1)) ?>

                    </div>


                    <div class="admin-profile-info">

                        <strong>
                            <?= htmlspecialchars($adminName) ?>
                        </strong>

                        <span>
                            <?= htmlspecialchars($adminEmail) ?>
                        </span>

                    </div>

                </div>

            </div>

        </header>


        <!-- PAGE CONTENT -->

        <section class="admin-content">

            <?= $pageContent ?? "" ?>

        </section>

    </main>

</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<script>

    const adminMenuToggle =
        document.getElementById("adminMenuToggle");

    const adminSidebar =
        document.querySelector(".admin-sidebar");

    if (adminMenuToggle && adminSidebar) {

        adminMenuToggle.addEventListener("click", function () {

            adminSidebar.classList.toggle("show");

        });

    }

</script>

</body>
</html>