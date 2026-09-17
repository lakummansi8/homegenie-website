<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration - HomeGenie</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <h2>Create Customer Account</h2>

    <form action="register-process.php" method="POST">

        <label>Full Name</label><br>
        <input type="text" name="full_name" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone</label><br>
        <input type="text" name="phone" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <label>Address</label><br>
        <textarea name="address" required></textarea><br><br>

        <label>City</label><br>
        <input type="text" name="city" required><br><br>

        <button type="submit">Register</button>

    </form>

    <br>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

    <p>
        <a href="../index.html">Back to Home</a>
    </p>

</body>
</html>