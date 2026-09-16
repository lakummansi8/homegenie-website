<?php

require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
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
    $full_name === "" ||
    $email === "" ||
    $phone === "" ||
    $password === "" ||
    $address === "" ||
    $city === ""
) {
    header("Location: register.php?error=empty");
    exit;
}

$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: register.php?error=email_exists");
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users 
    (full_name, email, phone, password, address, city, account_status)
    VALUES (?, ?, ?, ?, ?, ?, 'active')"
);

$stmt->bind_param(
    "ssssss",
    $full_name,
    $email,
    $phone,
    $hashed_password,
    $address,
    $city
);

if ($stmt->execute()) {
    header("Location: login.php?registered=success");
    exit;
}

echo "Registration failed: " . $stmt->error;
exit;