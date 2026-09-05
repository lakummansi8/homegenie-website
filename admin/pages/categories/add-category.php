<?php

require_once "../../../config/db.php";


/*
|--------------------------------------------------------------------------
| Add Category
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: categories.php");
    exit;
}


$categoryName = trim($_POST["category_name"] ?? "");
$description = trim($_POST["description"] ?? "");
$categoryStatus = trim($_POST["category_status"] ?? "Active");


/*
|--------------------------------------------------------------------------
| Validate Category Name
|--------------------------------------------------------------------------
*/

if ($categoryName === "") {
    header("Location: categories.php?error=category_name_required");
    exit;
}


/*
|--------------------------------------------------------------------------
| Validate Status
|--------------------------------------------------------------------------
*/

if (
    $categoryStatus !== "Active" &&
    $categoryStatus !== "Inactive"
) {
    header("Location: categories.php?error=invalid_status");
    exit;
}


/*
|--------------------------------------------------------------------------
| Category Image
|--------------------------------------------------------------------------
*/

$categoryImage = null;

if (
    isset($_FILES["category_image"]) &&
    $_FILES["category_image"]["error"] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES["category_image"]["error"] !== UPLOAD_ERR_OK) {
        die("Failed to upload category image.");
    }


    /*
    | Maximum 2 MB
    */

    if ($_FILES["category_image"]["size"] > 2 * 1024 * 1024) {
        die("Category image must be smaller than 2 MB.");
    }


    /*
    | Allowed image types
    */

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];


    /*
    | Check actual image
    */

    $imageInfo = getimagesize(
        $_FILES["category_image"]["tmp_name"]
    );

    if ($imageInfo === false) {
        die("Uploaded file is not a valid image.");
    }


    $mimeType = $imageInfo["mime"];

    if (!isset($allowedTypes[$mimeType])) {
        die("Only JPG, PNG and WEBP images are allowed.");
    }


    /*
    | Generate unique filename
    */

    $extension = $allowedTypes[$mimeType];

    $categoryImage =
        "category_" .
        time() .
        "_" .
        bin2hex(random_bytes(4)) .
        "." .
        $extension;


    /*
    | Upload directory
    */

    $uploadDirectory = "../../../assets/categories/";

    if (!is_dir($uploadDirectory)) {

        if (!mkdir($uploadDirectory, 0755, true)) {
            die("Failed to create image upload directory.");
        }
    }


    /*
    | Move image
    */

    $uploadPath = $uploadDirectory . $categoryImage;

    if (
        !move_uploaded_file(
            $_FILES["category_image"]["tmp_name"],
            $uploadPath
        )
    ) {
        die("Failed to save category image.");
    }
}


/*
|--------------------------------------------------------------------------
| Insert Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    INSERT INTO categories
    (
        category_name,
        category_image,
        description,
        category_status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?
    )
");


if (!$stmt) {
    die(
        "Failed to prepare category query: " .
        $conn->error
    );
}


$stmt->bind_param(
    "ssss",
    $categoryName,
    $categoryImage,
    $description,
    $categoryStatus
);


if ($stmt->execute()) {

    $stmt->close();

    header(
        "Location: categories.php?success=category_added"
    );

    exit;
}


$error = $stmt->error;

$stmt->close();

die("Failed to add category: " . $error);