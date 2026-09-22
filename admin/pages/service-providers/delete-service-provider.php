<?php

require_once "../../../config/db.php";

$providerId = $_GET['id'];

if($providerId == "")
{
    header("Location: service-providers.php?error=invalid_provider");
    exit;
}


// Get provider
$q = "select * from service_providers where provider_id = $providerId";
$res = mysqli_query($conn,$q);

if(mysqli_num_rows($res) == 0)
{
    header("Location: service-providers.php?error=provider_not_found");
    exit;
}

$provider = mysqli_fetch_array($res);


// Check provider services
$q = "select * from services where provider_id = $providerId";
$res = mysqli_query($conn,$q);

if(mysqli_num_rows($res) > 0)
{
    header("Location: service-providers.php?error=provider_has_services");
    exit;
}


// Delete provider
$q = "delete from service_providers where provider_id = $providerId";
$res = mysqli_query($conn,$q);

if($res)
{
    // Delete profile image
    if($provider['profile_image'] != "")
    {
        $imagePath = "../../../assets/providers/" . $provider['profile_image'];

        if(file_exists($imagePath))
        {
            unlink($imagePath);
        }
    }

    header("Location: service-providers.php?success=provider_deleted");
    exit;
}
else
{
    header("Location: service-providers.php?error=provider_delete_failed");
    exit;
}

?>