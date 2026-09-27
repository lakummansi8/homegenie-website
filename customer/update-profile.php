<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


$userId = $_SESSION["user_id"];


/* Get Form Data */

$fullName = $_POST["full_name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$city = $_POST["city"];


/* Check Empty Fields */

if (
    $fullName == "" ||
    $email == "" ||
    $phone == "" ||
    $address == "" ||
    $city == ""
)
{
    header("Location: profile.php?error=empty");
    exit;
}


/* Update Profile */

$q = "update users
      set
          full_name = '$fullName',
          email = '$email',
          phone = '$phone',
          address = '$address',
          city = '$city'
      where user_id = $userId";


$result = mysqli_query($conn, $q);


if ($result)
{
    $_SESSION["user_name"] = $fullName;

    header("Location: profile.php?updated=success");
    exit;
}


header("Location: profile.php?error=failed");
exit;

?>