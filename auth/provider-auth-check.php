<?php

session_start();

if (
    !isset($_SESSION["provider_logged_in"]) ||
    $_SESSION["provider_logged_in"] !== true
) {

    header("Location: ../auth/login.php");

    exit;
}