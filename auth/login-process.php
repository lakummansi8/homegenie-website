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


/* Check admin */

$stmt = $conn->prepare(
    "SELECT admin_id, full_name, email, password, account_status
     FROM admins
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $admin = $result->fetch_assoc();

    if (!password_verify($password, $admin["password"])) {
        header("Location: login.php?error=invalid");
        exit;
    }

    if ($admin["account_status"] !== "active") {
        header("Location: login.php?error=inactive");
        exit;
    }

    session_regenerate_id(true);

    $_SESSION["admin_logged_in"] = true;
    $_SESSION["admin_id"] = $admin["admin_id"];
    $_SESSION["admin_name"] = $admin["full_name"];
    $_SESSION["admin_email"] = $admin["email"];

    header("Location: ../admin/dashboard.php");
    exit;
}

$stmt->close();


/* Check customer */

$stmt = $conn->prepare(
    "SELECT user_id, full_name, email, password, account_status
     FROM users
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user["password"])) {
        header("Location: login.php?error=invalid");
        exit;
    }

    if ($user["account_status"] !== "Active") {
        header("Location: login.php?error=inactive");
        exit;
    }

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["user_name"] = $user["full_name"];
    $_SESSION["user_email"] = $user["email"];

    header("Location: ../customer/dashboard.php");
    exit;
}

$stmt->close();


/* Check provider */

$stmt = $conn->prepare(
    "SELECT provider_id, full_name, email, password, account_status
     FROM service_providers
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $provider = $result->fetch_assoc();

    if (!password_verify($password, $provider["password"])) {
        header("Location: login.php?error=invalid");
        exit;
    }

    if ($provider["account_status"] !== "Active") {
        header("Location: login.php?error=inactive");
        exit;
    }

    session_regenerate_id(true);

    $_SESSION["provider_logged_in"] = true;
    $_SESSION["provider_id"] = $provider["provider_id"];
    $_SESSION["provider_name"] = $provider["full_name"];
    $_SESSION["provider_email"] = $provider["email"];

    header("Location: ../provider/dashboard.php");
    exit;
}

$stmt->close();

header("Location: login.php?error=invalid");
exit;