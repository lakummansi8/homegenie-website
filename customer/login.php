<!DOCTYPE html>
<html>
<head>
    <title>Customer Login - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <h2>Customer Login</h2>

    <?php
    if (isset($_GET["error"])) {

        if ($_GET["error"] === "empty") {
            echo "<p>Please enter email and password.</p>";

        } elseif ($_GET["error"] === "invalid") {
            echo "<p>Invalid email or password.</p>";

        } elseif ($_GET["error"] === "inactive") {
            echo "<p>Your account is inactive.</p>";
        }
    }

    if (isset($_GET["registered"])) {
        echo "<p>Registration successful. Please login.</p>";
    }
    ?>

    <form action="login-process.php" method="POST">

        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>

    </form>

    <br>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

    <p>
        <a href="../index.php">Back to Home</a>
    </p>

</body>
</html>