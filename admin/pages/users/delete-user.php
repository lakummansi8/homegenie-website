<?php

require_once "../../../config/db.php";

$userId = $_GET['id'];

if($userId == "")
{
    header("Location: users.php?error=invalid_user");
    exit;
}

$q = "select * from users where user_id = $userId";
$res = mysqli_query($conn,$q);

if(mysqli_num_rows($res) == 0)
{
    header("Location: users.php?error=user_not_found");
    exit;
}

$q = "delete from users where user_id = $userId";
$res = mysqli_query($conn,$q);

if($res)
{
    header("Location: users.php?success=user_deleted");
    exit;
}
else
{
    header("Location: users.php?error=delete_failed");
    exit;
}

?>