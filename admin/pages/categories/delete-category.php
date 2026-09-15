<?php

require_once "../../../config/db.php";


/*
|--------------------------------------------------------------------------
| Get Category ID
|--------------------------------------------------------------------------
*/

$categoryId = (int)($_GET["id"] ?? 0);

if ($categoryId <= 0) {
    header("Location: categories.php?error=invalid_category");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        category_id,
        category_image
    FROM categories
    WHERE category_id = ?
    LIMIT 1
");

$stmt->bind_param("i", $categoryId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();

    header("Location: categories.php?error=category_not_found");
    exit;
}

$category = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Delete Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM categories
    WHERE category_id = ?
");

$stmt->bind_param("i", $categoryId);

if (!$stmt->execute()) {
    $stmt->close();

    header("Location: categories.php?error=delete_failed");
    exit;
}

$stmt->close();


/*
|--------------------------------------------------------------------------
| Delete Category Image
|--------------------------------------------------------------------------
*/

if (!empty($category["category_image"])) {

    $imagePath =
        "../../../assets/categories/" .
        $category["category_image"];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: categories.php?success=category_deleted");
exit;
