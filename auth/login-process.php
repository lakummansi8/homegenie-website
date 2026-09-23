```php
<?php

session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email == "" || $password == "")
    {
        header("Location: login.php?error=empty");
        exit;
    }


    /* Check Admin */

    $q = "SELECT * FROM admins WHERE email = '$email'";
    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) > 0)
    {
        $admin = mysqli_fetch_array($res);

        if (!password_verify($password, $admin["password"]))
        {
            header("Location: login.php?error=invalid");
            exit;
        }

        if ($admin["account_status"] != "active")
        {
            header("Location: login.php?error=inactive");
            exit;
        }

        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["admin_name"] = $admin["full_name"];
        $_SESSION["admin_email"] = $admin["email"];

        header("Location: ../admin/dashboard.php");
        exit;
    }


    /* Check Customer */

    $q = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) > 0)
    {
        $user = mysqli_fetch_array($res);

        if (!password_verify($password, $user["password"]))
        {
            header("Location: login.php?error=invalid");
            exit;
        }

        if ($user["account_status"] != "Active")
        {
            header("Location: login.php?error=inactive");
            exit;
        }

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user_name"] = $user["full_name"];
        $_SESSION["user_email"] = $user["email"];

        header("Location: ../customer/dashboard.php");
        exit;
    }


    /* Check Provider */

    $q = "SELECT * FROM service_providers WHERE email = '$email'";
    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) > 0)
    {
        $provider = mysqli_fetch_array($res);

        $passwordCorrect = password_verify($password, $provider["password"]);

        if (!$passwordCorrect && $password === $provider["password"])
        {
            $passwordCorrect = true;
        }

        if (!$passwordCorrect)
        {
            header("Location: login.php?error=invalid");
            exit;
        }

        if ($provider["account_status"] != "Active")
        {
            header("Location: login.php?error=inactive");
            exit;
        }

        $_SESSION["provider_logged_in"] = true;
        $_SESSION["provider_id"] = $provider["provider_id"];
        $_SESSION["provider_name"] = $provider["full_name"];
        $_SESSION["provider_email"] = $provider["email"];

        header("Location: ../provider/dashboard.php");
        exit;
    }


    /* Login Failed */

    header("Location: login.php?error=invalid");
    exit;
}


header("Location: login.php");
exit;

?>
```
