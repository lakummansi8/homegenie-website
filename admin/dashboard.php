<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | HomeGenie</title>

    <link
        rel="stylesheet"
        href="../bootstrap-5.3.8/css/bootstrap.min.css"
    >
</head>

<body>

<div class="container mt-5">

    <h1>Welcome to HomeGenie Admin</h1>

    <p>
        Welcome,
        <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>
    </p>

    <div class="alert alert-success">
        Admin login is working successfully.
    </div>

</div>

</body>
</html>