<?php
$servername="localhost";
$username="root";
$password="";
$databse="homegenie";


$conn=new mysqli($servername,$username,$password,$databse);

if($conn->connect_error)
    {
        die("connection failed".$conn->connect_error);
    }
    
?>