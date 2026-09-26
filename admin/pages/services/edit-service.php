<?php

$pageTitle = "Edit Service";
$pageCss = "services.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$id = $_GET["id"] ?? $_POST["service_id"] ?? "";


if ($id == "") {

    header("Location: services.php?error=invalid_service");

    exit;

}


/* Get Service */

$q = "select * from services where service_id = $id";

$res = mysqli_query($conn, $q);


if (mysqli_num_rows($res) == 0) {

    header("Location: services.php?error=service_not_found");

    exit;

}


$service = mysqli_fetch_array($res);


$error = "";


/* Update Service */

if (isset($_POST["update"])) {


    $serviceName = $_POST["service_name"];

    $categoryId = $_POST["category_id"];

    $providerId = $_POST["provider_id"];

    $price = $_POST["price"];

    $status = $_POST["service_status"];

    $description = $_POST["description"];


    if ($serviceName == "") {

        $error = "Service name is required.";

    }
    elseif ($categoryId == "") {

        $error = "Please select a category.";

    }
    elseif ($providerId == "") {

        $error = "Please select a provider.";

    }
    elseif ($price == "") {

        $error = "Price is required.";

    }
    elseif ($description == "") {

        $error = "Description is required.";

    }
    elseif (
        $status != "Active" &&
        $status != "Inactive"
    ) {

        $error = "Invalid service status.";

    }


    $imageName = $service["service_image"];


    /* New image */

    if ($error == "") {


        if (
            isset($_FILES["service_image"]) &&
            $_FILES["service_image"]["name"] != ""
        ) {


            $fileName = $_FILES["service_image"]["name"];

            $fileTmp = $_FILES["service_image"]["tmp_name"];

            $fileSize = $_FILES["service_image"]["size"];


            if ($fileSize > 2 * 1024 * 1024) {

                $error = "Service image must be smaller than 2 MB.";

            }
            else {


                $extension = strtolower(
                    pathinfo($fileName, PATHINFO_EXTENSION)
                );


                if (
                    $extension != "jpg" &&
                    $extension != "jpeg" &&
                    $extension != "png" &&
                    $extension != "webp"
                ) {

                    $error =
                        "Only JPG, JPEG, PNG and WEBP images are allowed.";

                }
                else {


                    $newImageName =
                        "service_" . time() . "." . $extension;


                    $uploadPath =
                        "../../../assets/services/" . $newImageName;


                    if (move_uploaded_file(
                        $fileTmp,
                        $uploadPath
                    )) {


                        $imageName = $newImageName;


                        /* Delete old image */

                        if ($service["service_image"] != "") {


                            $oldImage =
                                "../../../assets/services/" .
                                $service["service_image"];


                            if (file_exists($oldImage)) {

                                unlink($oldImage);

                            }

                        }


                    }
                    else {

                        $error = "Image upload failed.";

                    }

                }

            }

        }

    }


    /* Update Database */

    if ($error == "") {


        $q = "update services set

              category_id = '$categoryId',

              provider_id = '$providerId',

              service_name = '$serviceName',

              description = '$description',

              price = '$price',

              service_image = '$imageName',

              service_status = '$status'

              where service_id = $id";


        $res = mysqli_query($conn, $q);


        if ($res) {

            header(
                "Location: services.php?success=service_updated"
            );

            exit;

        }
        else {

            $error = "Service could not be updated.";

        }

    }


    /* Keep entered values */

    $service["service_name"] = $serviceName;

    $service["category_id"] = $categoryId;

    $service["provider_id"] = $providerId;

    $service["price"] = $price;

    $service["service_status"] = $status;

    $service["description"] = $description;

    $service["service_image"] = $imageName;

}


/* Get Categories */

$categories = mysqli_query(
    $conn,
    "select * from categories
     where category_status = 'Active'"
);


/* Get Providers */

$providers = mysqli_query(
    $conn,
    "select * from service_providers
     where account_status = 'Active'"
);


ob_start();

?>


<div class="admin-page-header">


    <div class="header-title">

        <h3>
            Edit Service
        </h3>

        <p class="text-muted">
            Update the service information below.
        </p>

    </div>


    <div class="header-actions">

        <a
            href="services.php"
            class="service-btn service-btn-secondary"
        >
            ← Back to Services
        </a>

    </div>


</div>


<?php if ($error != "") { ?>

    <div class="service-alert service-alert-danger">

        <?php echo $error; ?>

    </div>

<?php } ?>


<div class="service-form-card">


    <div class="service-form-header">

        <strong>
            Service Details
        </strong>

    </div>


    <div class="service-form-body">


        <form
            method="POST"
            action="edit-service.php?id=<?php echo $id; ?>"
            enctype="multipart/form-data"
        >


            <input
                type="hidden"
                name="service_id"
                value="<?php echo $id; ?>"
            >


            <div class="service-form-group">

                <label>
                    Service Name *
                </label>

                <input
                    type="text"
                    name="service_name"
                    value="<?php echo $service["service_name"]; ?>"
                    required
                >

            </div>


            <div class="service-form-row">


                <div class="service-form-group">

                    <label>
                        Category *
                    </label>

                    <select
                        name="category_id"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>


                        <?php while ($category = mysqli_fetch_array($categories)) { ?>


                            <option
                                value="<?php echo $category["category_id"]; ?>"
                                <?php

                                if (
                                    $category["category_id"] ==
                                    $service["category_id"]
                                ) {

                                    echo "selected";

                                }

                                ?>
                            >

                                <?php echo $category["category_name"]; ?>

                            </option>


                        <?php } ?>


                    </select>

                </div>


                <div class="service-form-group">

                    <label>
                        Provider *
                    </label>

                    <select
                        name="provider_id"
                        required
                    >

                        <option value="">
                            Select Provider
                        </option>


                        <?php while ($provider = mysqli_fetch_array($providers)) { ?>


                            <option
                                value="<?php echo $provider["provider_id"]; ?>"
                                <?php

                                if (
                                    $provider["provider_id"] ==
                                    $service["provider_id"]
                                ) {

                                    echo "selected";

                                }

                                ?>
                            >

                                <?php echo $provider["full_name"]; ?>

                            </option>


                        <?php } ?>


                    </select>

                </div>


            </div>


            <div class="service-form-row">


                <div class="service-form-group">

                    <label>
                        Hourly Price (₹) *
                    </label>

                    <input
                        type="number"
                        name="price"
                        value="<?php echo $service["price"]; ?>"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>


                <div class="service-form-group">

                    <label>
                        Status *
                    </label>

                    <select
                        name="service_status"
                    >

                        <option
                            value="Active"
                            <?php

                            if (
                                $service["service_status"] ==
                                "Active"
                            ) {

                                echo "selected";

                            }

                            ?>
                        >
                            Active
                        </option>


                        <option
                            value="Inactive"
                            <?php

                            if (
                                $service["service_status"] ==
                                "Inactive"
                            ) {

                                echo "selected";

                            }

                            ?>
                        >
                            Inactive
                        </option>

                    </select>

                </div>


            </div>


            <div class="service-form-group">

                <label>
                    Description *
                </label>

                <textarea
                    name="description"
                    rows="5"
                    required
                ><?php echo $service["description"]; ?></textarea>

            </div>


            <div class="service-form-group">

                <label>
                    Service Image
                </label>

                <input
                    type="file"
                    name="service_image"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small>
                    JPG, PNG or WEBP. Maximum size: 2 MB.
                    Leave empty to keep the existing image.
                </small>

            </div>


            <?php if ($service["service_image"] != "") { ?>


                <div class="service-form-group">

                    <label>
                        Current Service Image
                    </label>


                    <div class="current-service-image">

                        <img
                            src="../../../assets/services/<?php echo $service["service_image"]; ?>"
                            alt="Service Image"
                        >

                    </div>

                </div>


            <?php } ?>


            <div class="service-form-actions">


                <a
                    href="services.php"
                    class="service-btn service-btn-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    name="update"
                    class="service-btn service-btn-primary"
                >
                    Update Service
                </button>


            </div>


        </form>


    </div>


</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>