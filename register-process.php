<?php

require_once "config/db.php";

if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    header("Location: register.php");
    exit;
}

$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$password = $_POST["password"] ?? "";
$address = trim($_POST["address"] ?? "");
$city = trim($_POST["city"] ?? "");

if (
    $full_name == "" ||
    $email == "" ||
    $phone == "" ||
    $password == "" ||
    $address == "" ||
    $city == ""
)
{
    header("Location: register.php?error=empty");
    exit;
}

/* Check if email already exists */

$email = mysqli_real_escape_string($conn, $email);

$sql = "SELECT user_id FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0)
{
    header("Location: register.php?error=email_exists");
    exit;
}

/* Encrypt password */

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

/* Insert user */

$full_name = mysqli_real_escape_string($conn, $full_name);
$phone = mysqli_real_escape_string($conn, $phone);
$address = mysqli_real_escape_string($conn, $address);
$city = mysqli_real_escape_string($conn, $city);

$sql = "INSERT INTO users
        (full_name, email, phone, password, address, city, account_status)
        VALUES
        ('$full_name', '$email', '$phone', '$hashed_password', '$address', '$city', 'active')";

if (mysqli_query($conn, $sql))
{
    header("Location: auth/login.php?registered=success");
    exit;
}

echo "Registration failed: " . mysqli_error($conn);
exit;

?>