<?php

$pageTitle = "Delete Service";
$pageCss = "services.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


/*
|--------------------------------------------------------------------------
| Get Service ID
|--------------------------------------------------------------------------
*/

$serviceId = (int)($_GET["id"] ?? 0);

if ($serviceId <= 0) {
    header("Location: services.php?error=invalid_service");
    exit;
}


/*
|--------------------------------------------------------------------------
| Fetch Service
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        service_id,
        service_name,
        service_image
    FROM services
    WHERE service_id = ?
    LIMIT 1
");

$stmt->bind_param("i", $serviceId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: services.php?error=service_not_found");
    exit;
}

$service = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Delete Service
|--------------------------------------------------------------------------
*/

$deleteStmt = $conn->prepare("
    DELETE FROM services
    WHERE service_id = ?
");

$deleteStmt->bind_param("i", $serviceId);

if ($deleteStmt->execute()) {

    $deleteStmt->close();


    /*
    |--------------------------------------------------------------------------
    | Delete Service Image
    |--------------------------------------------------------------------------
    */

    if (!empty($service["service_image"])) {

        $imagePath =
            "../../../assets/services/" .
            $service["service_image"];

        if (
            file_exists($imagePath) &&
            is_file($imagePath)
        ) {
            unlink($imagePath);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    header(
        "Location: services.php?success=service_deleted"
    );

    exit;

} else {

    $deleteStmt->close();

    header(
        "Location: services.php?error=service_delete_failed"
    );

    exit;
}
