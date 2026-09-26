<?php

$pageTitle = "Edit Category";
$pageCss = "categories.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$categoryId = $_GET["id"] ?? "";


if ($categoryId == "") {

    header("Location: categories.php?error=invalid_category");

    exit;

}


/* Get category */

$q = "select * from categories where category_id = $categoryId";

$res = mysqli_query($conn, $q);


if (mysqli_num_rows($res) == 0) {

    header("Location: categories.php?error=category_not_found");

    exit;

}


$category = mysqli_fetch_array($res);


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


        $oldImage = $category["category_image"];

        $categoryImage = $oldImage;


        /* New image */

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


                    /* Delete old image */

                    if ($oldImage != "") {

                        $oldImagePath =
                            "../../../assets/categories/" . $oldImage;


                        if (file_exists($oldImagePath)) {

                            unlink($oldImagePath);

                        }

                    }

                }

            }

        }


        if ($error == "") {


            $q = "update categories set
                  category_name = '$categoryName',
                  category_image = '$categoryImage',
                  description = '$description',
                  category_status = '$categoryStatus'
                  where category_id = $categoryId";


            $res = mysqli_query($conn, $q);


            if ($res) {

                header(
                    "Location: categories.php?success=category_updated"
                );

                exit;

            }
            else {

                $error = "Unable to update category.";

            }

        }

    }

}


/* Get updated category */

$q = "select * from categories where category_id = $categoryId";

$res = mysqli_query($conn, $q);


if (mysqli_num_rows($res) == 0) {

    header("Location: categories.php?error=category_not_found");

    exit;

}


$category = mysqli_fetch_array($res);


ob_start();

?>


<div class="admin-page-header">


    <div class="header-title">

        <h3>
            Edit Category
        </h3>

        <p class="text-muted">
            Update the details of this service category.
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
            action="edit-category.php?id=<?php echo $category["category_id"]; ?>"
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
                    value="<?php echo $category["category_name"]; ?>"
                    required
                >

            </div>


            <?php if ($category["category_image"] != "") { ?>


                <div class="category-form-group">

                    <label>
                        Current Category Image
                    </label>


                    <div class="current-category-image">

                        <img
                            src="../../../assets/categories/<?php echo $category["category_image"]; ?>"
                            alt="Category Image"
                        >

                    </div>

                </div>


            <?php } ?>


            <div class="category-form-group">

                <label for="categoryImage">
                    Change Category Image
                </label>

                <input
                    type="file"
                    id="categoryImage"
                    name="category_image"
                    accept="image/png, image/jpeg, image/webp"
                >

                <small>
                    JPG, PNG or WEBP. Maximum size: 2 MB.
                    Leave empty to keep the current image.
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
                ><?php echo $category["description"]; ?></textarea>

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
                        <?php
                        if ($category["category_status"] == "Active")
                        {
                            echo "selected";
                        }
                        ?>
                    >
                        Active
                    </option>


                    <option
                        value="Inactive"
                        <?php
                        if ($category["category_status"] == "Inactive")
                        {
                            echo "selected";
                        }
                        ?>
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
                    Save Changes
                </button>

            </div>


        </form>


    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>