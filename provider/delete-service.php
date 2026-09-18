<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

$serviceId = $_GET["id"] ?? "";

if ($serviceId === "" || !is_numeric($serviceId)) {
    header("Location: services.php");
    exit;
}

$stmt = $conn->prepare(
    "DELETE FROM services
     WHERE service_id = ?
     AND provider_id = ?"
);

$stmt->bind_param("ii", $serviceId, $providerId);
$stmt->execute();

$stmt->close();

header("Location: services.php?deleted=1");
exit;