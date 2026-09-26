<?php

$pageTitle = "Add Category";
$pageCss = "categories.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$categoryName = "";
$description = "";
$categoryStatus = "Active";

$error = "";


if (isset($_POST["submit"])) {

    $categoryName = $_POST["category_name"];
    $description = $_POST["description"];
    $categoryStatus = $_POST["category_status"];


    if ($categoryName == "") {

        $error = "Category name is required.";

    }
    elseif (
        $categoryStatus != "Active" &&
        $categoryStatus != "Inactive"
    ) {

        $error = "Invalid category status.";

    }


    if ($error == "") {


        $categoryImage = "";


        if (
            isset($_FILES["category_image"]) &&
            $_FILES["category_image"]["name"] != ""
        ) {


            $imageName = $_FILES["category_image"]["name"];

            $imageSize = $_FILES["category_image"]["size"];

            $imageTmp = $_FILES["category_image"]["tmp_name"];


            if ($imageSize > 2 * 1024 * 1024) {

                $error = "Category image must be smaller than 2 MB.";

            }
            else {


                $imageType = strtolower(
                    pathinfo($imageName, PATHINFO_EXTENSION)
                );


                if (
                    $imageType != "jpg" &&
                    $imageType != "jpeg" &&
                    $imageType != "png" &&
                    $imageType != "webp"
                ) {

                    $error = "Only JPG, PNG and WEBP images are allowed.";

                }
                else {


                    $newImageName =
                        "category_" . time() . "." . $imageType;


                    $uploadPath =
                        "../../../assets/categories/" . $newImageName;


                    move_uploaded_file(
                        $imageTmp,
                        $uploadPath
                    );


                    $categoryImage = $newImageName;

                }

            }

        }


        if ($error == "") {


            $q = "insert into categories
            (
                category_name,
                category_image,
                description,
                category_status
            )
            values
            (
                '$categoryName',
                '$categoryImage',
                '$description',
                '$categoryStatus'
            )";


            $res = mysqli_query($conn, $q);


            if ($res) {

                header(
                    "Location: categories.php?success=category_added"
                );

                exit;

            }
            else {

                $error = "Unable to add category.";

            }

        }

    }

}


ob_start();

?>


<div class="admin-page-header">


    <div class="header-title">

        <h3>
            Add Category
        </h3>

        <p class="text-muted">
            Create a new service category for HomeGenie.
        </p>

    </div>


    <div class="header-actions">

        <a
            href="categories.php"
            class="category-btn category-btn-secondary"
        >
            ← Back to Categories
        </a>

    </div>


</div>


<?php if ($error != "") { ?>

    <div class="category-alert category-alert-danger">

        <?php echo $error; ?>

    </div>

<?php } ?>


<div class="category-form-card">


    <div class="category-form-header">

        <strong>
            Category Information
        </strong>

    </div>


    <div class="category-form-body">


        <form
            method="POST"
            action="add-category.php"
            enctype="multipart/form-data"
        >


            <div class="category-form-group">

                <label for="categoryName">
                    Category Name
                </label>

                <input
                    type="text"
                    id="categoryName"
                    name="category_name"
                    value="<?php echo $categoryName; ?>"
                    placeholder="Enter category name"
                    required
                >

            </div>


            <div class="category-form-group">

                <label for="categoryImage">
                    Category Image
                </label>

                <input
                    type="file"
                    id="categoryImage"
                    name="category_image"
                    accept="image/png, image/jpeg, image/webp"
                >

                <small>
                    JPG, PNG or WEBP. Maximum size: 2 MB.
                </small>

            </div>


            <div class="category-form-group">

                <label for="categoryDescription">
                    Description
                </label>

                <textarea
                    id="categoryDescription"
                    name="description"
                    rows="5"
                    placeholder="Enter category description"
                ><?php echo $description; ?></textarea>

            </div>


            <div class="category-form-group">

                <label for="categoryStatus">
                    Status
                </label>

                <select
                    id="categoryStatus"
                    name="category_status"
                >

                    <option
                        value="Active"
                        <?php if ($categoryStatus == "Active") echo "selected"; ?>
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        <?php if ($categoryStatus == "Inactive") echo "selected"; ?>
                    >
                        Inactive
                    </option>

                </select>

            </div>


            <div class="category-form-actions">

                <a
                    href="categories.php"
                    class="category-btn category-btn-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    name="submit"
                    class="category-btn category-btn-primary"
                >
                    Save Category
                </button>

            </div>


        </form>


    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>