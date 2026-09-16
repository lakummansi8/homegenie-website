<?php

session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    header("Location: login.php?error=empty");
    exit;
}

$stmt = $conn->prepare(
    "SELECT user_id, full_name, email, password, account_status
     FROM users
     WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($password, $user["password"])) {
    header("Location: login.php?error=invalid");
    exit;
}

if ($user["account_status"] !== "Active") {
    header("Location: login.php?error=inactive");
    exit;
}

$_SESSION["user_id"] = $user["user_id"];
$_SESSION["user_name"] = $user["full_name"];
$_SESSION["user_email"] = $user["email"];

header("Location: dashboard.php");
exit;