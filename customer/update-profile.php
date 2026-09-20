<?php

session_start();
require_once "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user_id"];

$fullName = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$address = trim($_POST["address"] ?? "");
$city = trim($_POST["city"] ?? "");

if (
    $fullName === "" ||
    $email === "" ||
    $phone === "" ||
    $address === "" ||
    $city === ""
) {
    header("Location: profile.php?error=empty");
    exit;
}

$stmt = $conn->prepare(
    "UPDATE users
     SET full_name = ?, email = ?, phone = ?, address = ?, city = ?
     WHERE user_id = ?"
);

$stmt->bind_param(
    "sssssi",
    $fullName,
    $email,
    $phone,
    $address,
    $city,
    $userId
);

$stmt->execute();

$_SESSION["user_name"] = $fullName;

header("Location: profile.php?updated=success");
exit;