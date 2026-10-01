<?php

require_once "../../../config/db.php";

$contactId = $_GET["id"];

if ($contactId == "" || !is_numeric($contactId))
{
    header("Location: contacts.php");
    exit;
}

$sql = "update contact
        set message_status = 'Read'
        where contact_id = $contactId";

mysqli_query($conn, $sql);

header("Location: contacts.php");
exit;

?>