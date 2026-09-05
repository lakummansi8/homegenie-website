<?php

$error = $_GET["error"] ?? "";

$errorMessage = "";

switch ($error) {

    case "empty":
        $errorMessage = "Please enter both email and password.";
        break;

    case "invalid":
        $errorMessage = "Invalid email or password.";
        break;

    case "inactive":
        $errorMessage = "Your admin account is inactive.";
        break;
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

    <title>Admin Login - HomeGenie</title>

    <link
        rel="stylesheet"
        href="../css/auth/login.css?v=2"
    >

</head>


<body>

<div class="login-container">

    <div class="login-card">

        <h2>HomeGenie Admin</h2>

        <p class="login-subtitle">
            Sign in to access the Admin Panel
        </p>


        <?php if ($errorMessage !== ""): ?>

            <div class="login-error">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>

        <?php endif; ?>


        <form
            method="POST"
            action="login-process.php"
        >

            <div class="input-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    autocomplete="email"
                    required
                >

            </div>


            <div class="input-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <button
                type="submit"
                name="login"
            >
                Sign In
            </button>

        </form>

    </div>

</div>

</body>

</html>