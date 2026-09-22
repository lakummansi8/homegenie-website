<?php

$pageTitle = "Add Service";
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

/* Get Categories */

$categories = mysqli_query($conn, "SELECT * FROM categories WHERE category_status = 'Active'");


/* Get Providers */

$providers = [];

if(isset($_GET['category_id']))
{
    $categoryId = $_GET['category_id'];

    $sql = "SELECT * FROM service_providers
            WHERE category_id = $categoryId
            AND account_status = 'Active'
            ORDER BY full_name";

    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result))
    {
        $providers[] = $row;
    }

    header("Content-Type: application/json");
    echo json_encode($providers);
    exit;
}


/* Add Service */

if(isset($_POST['add']))
{
    $serviceName = $_POST['service_name'];
    $categoryId = $_POST['category_id'];
    $providerId = $_POST['provider_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $serviceStatus = $_POST['service_status'];

    if($serviceName == "")
    {
        $error = "Service name is required.";
    }
    elseif($categoryId == "")
    {
        $error = "Please select a category.";
    }
    elseif($providerId == "")
    {
        $error = "Please select a provider.";
    }
    elseif($description == "")
    {
        $error = "Description is required.";
    }
    elseif($price == "")
    {
        $error = "Price is required.";
    }
    else
    {
        $imageName = "";

        if(isset($_FILES['service_image']) && $_FILES['service_image']['name'] != "")
        {
            $fileName = $_FILES['service_image']['name'];
            $fileTmp = $_FILES['service_image']['tmp_name'];

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if($extension != "jpg" &&
               $extension != "jpeg" &&
               $extension != "png" &&
               $extension != "webp")
            {
                $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";
            }
            else
            {
                $imageName = "service_" . time() . "." . $extension;

                $uploadPath = "../../../assets/services/" . $imageName;

                if(!move_uploaded_file($fileTmp, $uploadPath))
                {
                    $error = "Image upload failed.";
                }
            }
        }
    }


    /* Insert Service */

    if($error == "")
    {
        $sql = "INSERT INTO services
                (category_id, provider_id, service_name, description, price, service_image, service_status)
                VALUES
                ('$categoryId', '$providerId', '$serviceName', '$description', '$price', '$imageName', '$serviceStatus')";

        $result = mysqli_query($conn, $sql);

        if($result)
        {
            header("Location: services.php?success=service_added");
            exit;
        }
        else
        {
            $error = "Service could not be added.";
        }
    }
}

ob_start();
?>

<div class="row mb-4">

    <div class="col-md-8">

        <h3>Add Service</h3>

        <p class="text-muted">
            Add a new service to your HomeGenie service list.
        </p>

    </div>

    <div class="col-md-4 text-md-end">

        <a href="services.php" class="btn btn-secondary">
            &larr; Back to Services
        </a>

    </div>

</div>


<?php if($error != "") { ?>

    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>

<?php } ?>


<div class="card">

    <div class="card-header bg-dark text-white">
        <strong>Service Details</strong>
    </div>

    <div class="card-body">

        <form method="POST" enctype="multipart/form-data">


            <div class="mb-3">

                <label class="form-label">
                    Service Name *
                </label>

                <input type="text"
                       class="form-control"
                       name="service_name"
                       value="<?php echo $serviceName; ?>"
                       required>

            </div>


            <div class="row">


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Category *
                    </label>

                    <select class="form-select"
                            name="category_id"
                            id="category"
                            required>

                        <option value="">
                            Select Category
                        </option>

                        <?php while($category = mysqli_fetch_array($categories)) { ?>

                            <option value="<?php echo $category['category_id']; ?>">

                                <?php echo $category['category_name']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Provider *
                    </label>

                    <select class="form-select"
                            name="provider_id"
                            id="provider"
                            required>

                        <option value="">
                            Select Category First
                        </option>

                    </select>

                </div>

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Description *
                </label>

                <textarea class="form-control"
                          name="description"
                          rows="5"
                          required><?php echo $description; ?></textarea>

            </div>


            <div class="row">


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Hourly Price (₹) *
                    </label>

                    <input type="number"
                           class="form-control"
                           name="price"
                           value="<?php echo $price; ?>"
                           min="0"
                           step="0.01"
                           required>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Status *
                    </label>

                    <select class="form-select"
                            name="service_status">

                        <option value="Active">
                            Active
                        </option>

                        <option value="Inactive">
                            Inactive
                        </option>

                    </select>

                </div>

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Service Image
                </label>

                <input type="file"
                       class="form-control"
                       name="service_image"
                       accept=".jpg,.jpeg,.png,.webp">

                <div class="form-text">
                    JPG, PNG or WEBP.
                </div>

            </div>


            <hr>


            <div class="d-flex justify-content-between">

                <a href="services.php"
                   class="btn btn-secondary">

                    Cancel

                </a>


                <button type="submit"
                        name="add"
                        class="btn btn-primary">

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

    provider.innerHTML = "<option value=''>Loading...</option>";

    if(categoryId == "")
    {
        provider.innerHTML = "<option value=''>Select Category First</option>";
        return;
    }

    fetch("add-service.php?category_id=" + categoryId)

    .then(function(response)
    {
        return response.json();
    })

    .then(function(data)
    {
        provider.innerHTML = "<option value=''>Select Provider</option>";

        for(var i = 0; i < data.length; i++)
        {
            provider.innerHTML +=
                "<option value='" + data[i].provider_id + "'>" +
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