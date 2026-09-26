<?php

$pageTitle = "Add Service";
$pageCss = "services.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$serviceName = "";
$categoryId = "";
$providerId = "";
$description = "";
$price = "";
$serviceStatus = "Active";

$error = "";


/*
    Load providers for AJAX
*/

if (isset($_GET["category_id"])) {

    $categoryId = $_GET["category_id"];


    $q = "select * from service_providers
          where category_id = $categoryId
          and account_status = 'Active'
          order by full_name";


    $res = mysqli_query($conn, $q);


    $providers = [];


    while ($provider = mysqli_fetch_array($res)) {

        $providers[] = $provider;

    }


    header("Content-Type: application/json");

    echo json_encode($providers);

    exit;

}


/* Get Categories */

$categories = mysqli_query(
    $conn,
    "select * from categories
     where category_status = 'Active'"
);


/* Add Service */

if (isset($_POST["add"])) {


    $serviceName = $_POST["service_name"];

    $categoryId = $_POST["category_id"];

    $providerId = $_POST["provider_id"];

    $description = $_POST["description"];

    $price = $_POST["price"];

    $serviceStatus = $_POST["service_status"];


    if ($serviceName == "") {

        $error = "Service name is required.";

    }
    elseif ($categoryId == "") {

        $error = "Please select a category.";

    }
    elseif ($providerId == "") {

        $error = "Please select a provider.";

    }
    elseif ($description == "") {

        $error = "Description is required.";

    }
    elseif ($price == "") {

        $error = "Price is required.";

    }
    elseif (
        $serviceStatus != "Active" &&
        $serviceStatus != "Inactive"
    ) {

        $error = "Invalid service status.";

    }


    /* Image */

    $imageName = "";


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


                    $imageName =
                        "service_" . time() . "." . $extension;


                    $uploadPath =
                        "../../../assets/services/" . $imageName;


                    if (!move_uploaded_file(
                        $fileTmp,
                        $uploadPath
                    )) {

                        $error = "Image upload failed.";

                    }

                }

            }

        }

    }


    /* Insert */

    if ($error == "") {


        $q = "insert into services
        (
            category_id,
            provider_id,
            service_name,
            description,
            price,
            service_image,
            service_status
        )
        values
        (
            '$categoryId',
            '$providerId',
            '$serviceName',
            '$description',
            '$price',
            '$imageName',
            '$serviceStatus'
        )";


        $res = mysqli_query($conn, $q);


        if ($res) {

            header(
                "Location: services.php?success=service_added"
            );

            exit;

        }
        else {

            $error = "Service could not be added.";

        }

    }

}


ob_start();

?>


<div class="admin-page-header">


    <div class="header-title">

        <h3>
            Add Service
        </h3>

        <p class="text-muted">
            Add a new service to your HomeGenie service list.
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
            action="add-service.php"
            enctype="multipart/form-data"
        >


            <div class="service-form-group">

                <label>
                    Service Name *
                </label>

                <input
                    type="text"
                    name="service_name"
                    value="<?php echo $serviceName; ?>"
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
                        id="category"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>


                        <?php while ($category = mysqli_fetch_array($categories)) { ?>


                            <option
                                value="<?php echo $category["category_id"]; ?>"
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
                        id="provider"
                        required
                    >

                        <option value="">
                            Select Category First
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
                ><?php echo $description; ?></textarea>

            </div>


            <div class="service-form-row">


                <div class="service-form-group">

                    <label>
                        Hourly Price (₹) *
                    </label>

                    <input
                        type="number"
                        name="price"
                        value="<?php echo $price; ?>"
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

                        <option value="Active">
                            Active
                        </option>

                        <option value="Inactive">
                            Inactive
                        </option>

                    </select>

                </div>


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
                </small>

            </div>


            <div class="service-form-actions">


                <a
                    href="services.php"
                    class="service-btn service-btn-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    name="add"
                    class="service-btn service-btn-primary"
                >
                    Add Service
                </button>


            </div>


        </form>


    </div>


</div>


<script>

document.getElementById("category").addEventListener("change", function()
{

    var categoryId = this.value;

    var provider = document.getElementById("provider");


    provider.innerHTML =
        "<option value=''>Loading...</option>";


    if (categoryId == "")
    {

        provider.innerHTML =
            "<option value=''>Select Category First</option>";

        return;

    }


    fetch("add-service.php?category_id=" + categoryId)

    .then(function(response)
    {
        return response.json();
    })

    .then(function(data)
    {

        provider.innerHTML =
            "<option value=''>Select Provider</option>";


        for (var i = 0; i < data.length; i++)
        {

            provider.innerHTML +=
                "<option value='" +
                data[i].provider_id +
                "'>" +
                data[i].full_name +
                "</option>";

        }

    });

});

</script>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>