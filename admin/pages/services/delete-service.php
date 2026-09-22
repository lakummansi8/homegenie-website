<?php

require_once "../../../config/db.php";


$id = $_GET['id'];

if($id == "")
{
    header("Location: services.php?error=invalid_service");
    exit;
}


$q = "select * from services where service_id = $id";
$res = mysqli_query($conn,$q);


if(mysqli_num_rows($res) == 0)
{
    header("Location: services.php?error=service_not_found");
    exit;
}


$service = mysqli_fetch_array($res);


$q = "delete from services where service_id = $id";
$res = mysqli_query($conn,$q);


if($res)
{
    if($service['service_image'] != "")
    {
        $imagePath = "../../../assets/services/" . $service['service_image'];

        if(file_exists($imagePath))
        {
            unlink($imagePath);
        }
    }


    header("Location: services.php?success=service_deleted");
    exit;
}
else
{
    header("Location: services.php?error=service_delete_failed");
    exit;
}

?>