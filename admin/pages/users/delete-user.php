<?php

/*
|--------------------------------------------------------------------------
| DELETE CUSTOMER
|--------------------------------------------------------------------------
| This file deletes a customer from the users table.
|--------------------------------------------------------------------------
*/

require_once "../../../config/db.php";


/*
|--------------------------------------------------------------------------
| GET CUSTOMER ID
|--------------------------------------------------------------------------
*/

$userId = (int) (
    $_GET["id"] ??
    $_POST["user_id"] ??
    0
);


/*
|--------------------------------------------------------------------------
| INVALID ID
|--------------------------------------------------------------------------
*/

if ($userId <= 0) {

    header("Location: users.php?error=invalid_user");
    exit;
}


/*
|--------------------------------------------------------------------------
| CHECK IF CUSTOMER EXISTS
|--------------------------------------------------------------------------
*/

$checkStmt = $conn->prepare("
    SELECT user_id
    FROM users
    WHERE user_id = ?
    LIMIT 1
");

if (!$checkStmt) {

    header("Location: users.php?error=delete_failed");
    exit;
}


$checkStmt->bind_param(
    "i",
    $userId
);

$checkStmt->execute();

$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {

    $checkStmt->close();

    header("Location: users.php?error=user_not_found");
    exit;
}

$checkStmt->close();


/*
|--------------------------------------------------------------------------
| DELETE CUSTOMER
|--------------------------------------------------------------------------
*/

$deleteStmt = $conn->prepare("
    DELETE FROM users
    WHERE user_id = ?
    LIMIT 1
");


if (!$deleteStmt) {

    header("Location: users.php?error=delete_failed");
    exit;
}


$deleteStmt->bind_param(
    "i",
    $userId
);


if ($deleteStmt->execute()) {

    $deleteStmt->close();

    header("Location: users.php?success=user_deleted");
    exit;

}


$deleteStmt->close();

header("Location: users.php?error=delete_failed");
exit;

?>
