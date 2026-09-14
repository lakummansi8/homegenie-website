<?php

/*
|--------------------------------------------------------------------------
| Page Configuration
|--------------------------------------------------------------------------
*/

$pageTitle = "Delete Service Provider";
$pageCss = "service-providers.css";

$assetPath = "../../../";
$adminPath = "../../";


/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/

require_once "../../../config/db.php";


/*
|--------------------------------------------------------------------------
| Get Provider ID
|--------------------------------------------------------------------------
*/

$providerId = (int)($_GET["id"] ?? 0);

if ($providerId <= 0) {
    header("Location: service-providers.php?error=invalid_provider");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Provider Details
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT provider_id, full_name, profile_image
    FROM service_providers
    WHERE provider_id = ?
    LIMIT 1
");

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();
$provider = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Provider Not Found
|--------------------------------------------------------------------------
*/

if (!$provider) {
    header("Location: service-providers.php?error=provider_not_found");
    exit;
}


/*
|--------------------------------------------------------------------------
| Check Related Services
|--------------------------------------------------------------------------
|
| A provider may already be connected to services.
| We should not delete the provider while those services
| are still using provider_id.
|
*/

$stmt = $conn->prepare("
    SELECT COUNT(*) AS service_count
    FROM services
    WHERE provider_id = ?
");

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

$serviceCount = (int)($row["service_count"] ?? 0);


/*
|--------------------------------------------------------------------------
| Stop Delete If Provider Has Services
|--------------------------------------------------------------------------
*/

if ($serviceCount > 0) {
    header("Location: service-providers.php?error=provider_has_services");
    exit;
}


/*
|--------------------------------------------------------------------------
| Delete Provider
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM service_providers
    WHERE provider_id = ?
");

$stmt->bind_param("i", $providerId);

if ($stmt->execute()) {

    /*
    |--------------------------------------------------------------------------
    | Delete Profile Image
    |--------------------------------------------------------------------------
    */

    if (!empty($provider["profile_image"])) {

        $imagePath = "../../../assets/providers/" . $provider["profile_image"];

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt->close();

    header("Location: service-providers.php?success=provider_deleted");
    exit;
}


/*
|--------------------------------------------------------------------------
| Delete Failed
|--------------------------------------------------------------------------
*/

$stmt->close();

header("Location: service-providers.php?error=provider_delete_failed");
exit;
