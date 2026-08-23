<?php
include "db.php";

$full_name=$_POST['full_name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);
$address=$_POST['address'];
$city=$_POST['city'];


$sql="insert into users(full_name,email,phone,password,address,city)
values
('$full_name','$email','$phone','$password','$address','$city')";

if($conn->query($sql)==TRUE)
    {
        header("Location:../pages/auth/login/login.html");
    }
    else
        {
            echo "error: ".$conn->error;
        }

    $conn.close();
?>