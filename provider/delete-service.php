```php
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$serviceId = $_GET["id"];


/* Check service ID */

if ($serviceId == "" || !is_numeric($serviceId)) {

    header("Location: services.php");
    exit;
}


/* Delete service */

$sql = "DELETE FROM services
        WHERE service_id = $serviceId
        AND provider_id = $providerId";

mysqli_query($conn, $sql);


/* Go back to services page */

header("Location: services.php?deleted=1");
exit;
?>

