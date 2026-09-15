<?php

session_start();

require_once "../config/db.php";


/*
|--------------------------------------------------------------------------
| Make sure the request came from the login form
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: login.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| Get email and password from the form
|--------------------------------------------------------------------------
*/

$email = trim($_POST["email"] ?? "");

$password = $_POST["password"] ?? "";


/*
|--------------------------------------------------------------------------
| Check empty fields
|--------------------------------------------------------------------------
*/

if ($email === "" || $password === "") {

    header("Location: login.php?error=empty");

    exit;
}


/*
|--------------------------------------------------------------------------
| Find admin by email
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        admin_id,
        full_name,
        email,
        password,
        account_status
     FROM admins
     WHERE email = ?
     LIMIT 1"
);


if (!$stmt) {

    die("Database query preparation failed: " . $conn->error);
}


$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Check whether admin exists
|--------------------------------------------------------------------------
*/

if ($result->num_rows !== 1) {

    header("Location: login.php?error=invalid");

    exit;
}


$admin = $result->fetch_assoc();


/*
|--------------------------------------------------------------------------
| Verify password
|--------------------------------------------------------------------------
*/

if (!password_verify($password, $admin["password"])) {

    header("Location: login.php?error=invalid");

    exit;
}


/*
|--------------------------------------------------------------------------
| Check account status
|--------------------------------------------------------------------------
*/

if ($admin["account_status"] !== "active") {

    header("Location: login.php?error=inactive");

    exit;
}


/*
|--------------------------------------------------------------------------
| Create admin session
|--------------------------------------------------------------------------
*/

session_regenerate_id(true);

$_SESSION["admin_logged_in"] = true;

$_SESSION["admin_id"] = $admin["admin_id"];

$_SESSION["admin_name"] = $admin["full_name"];

$_SESSION["admin_email"] = $admin["email"];


/*
|--------------------------------------------------------------------------
| Login successful
|--------------------------------------------------------------------------
*/

header("Location: ../admin/dashboard.php");

exit;