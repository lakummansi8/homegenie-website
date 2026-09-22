<?php

$pageTitle = "Edit Service";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$id = $_GET['id'] ?? $_POST['service_id'] ?? 0;

if($id == 0)
{
    header("Location: services.php");
    exit;
}

$sql = "SELECT * FROM services WHERE service_id = $id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0)
{
    header("Location: services.php");
    exit;
}

$service = mysqli_fetch_array($result);

$error = "";

if(isset($_POST['update']))
{
    $serviceName = $_POST['service_name'];
    $categoryId = $_POST['category_id'];
    $providerId = $_POST['provider_id'];
    $price = $_POST['price'];
    $status = $_POST['service_status'];
    $description = $_POST['description'];

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
    elseif($price == "")
    {
        $error = "Price is required.";
    }
    elseif($description == "")
    {
        $error = "Description is required.";
    }
    else
    {
        $imageName = $service['service_image'];

        if(isset($_FILES['service_image']) && $_FILES['service_image']['name'] != "")
        {
            $fileName = $_FILES['service_image']['name'];
            $fileTmp = $_FILES['service_image']['tmp_name'];

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if($extension != "jpg" && $extension != "jpeg" && $extension != "png" && $extension != "webp")
            {
                $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";
            }
            else
            {
                $imageName = "service_" . time() . "." . $extension;

                $uploadPath = "../../../assets/services/" . $imageName;

                if(move_uploaded_file($fileTmp, $uploadPath))
                {
                    if($service['service_image'] != "")
                    {
                        $oldImage = "../../../assets/services/" . $service['service_image'];

                        if(file_exists($oldImage))
                        {
                            unlink($oldImage);
                        }
                    }
                }
                else
                {
                    $error = "Image upload failed.";
                    $imageName = $service['service_image'];
                }
            }
        }
    }

    if($error == "")
    {
        $sql = "UPDATE services SET
                category_id = '$categoryId',
                provider_id = '$providerId',
                service_name = '$serviceName',
                description = '$description',
                price = '$price',
                service_image = '$imageName',
                service_status = '$status'
                WHERE service_id = $id";

        $result = mysqli_query($conn, $sql);

        if($result)
        {
            header("Location: services.php?success=service_updated");
            exit;
        }
        else
        {
            $error = "Service could not be updated.";
        }
    }

    $service['service_name'] = $serviceName;
    $service['category_id'] = $categoryId;
    $service['provider_id'] = $providerId;
    $service['price'] = $price;
    $service['service_status'] = $status;
    $service['description'] = $description;
    $service['service_image'] = $imageName;
}

$categories = mysqli_query($conn, "SELECT * FROM categories WHERE category_status = 'Active'");

$providers = mysqli_query($conn, "SELECT * FROM service_providers");

ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h3>Edit Service</h3>
        <p class="text-muted">Update the service information below.</p>
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

            <input type="hidden" name="service_id" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label class="form-label">Service Name *</label>

                <input type="text"
                       class="form-control"
                       name="service_name"
                       value="<?php echo $service['service_name']; ?>"
                       required>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Category *</label>

                    <select class="form-select" name="category_id" required>

                        <option value="">Select Category</option>

                        <?php while($category = mysqli_fetch_array($categories)) { ?>

                            <option value="<?php echo $category['category_id']; ?>"
                                <?php
                                if($category['category_id'] == $service['category_id'])
                                {
                                    echo "selected";
                                }
                                ?>>
                                <?php echo $category['category_name']; ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">Provider *</label>

                    <select class="form-select" name="provider_id" required>

                        <option value="">Select Provider</option>

                        <?php while($provider = mysqli_fetch_array($providers)) { ?>

                            <option value="<?php echo $provider['provider_id']; ?>"
                                <?php
                                if($provider['provider_id'] == $service['provider_id'])
                                {
                                    echo "selected";
                                }
                                ?>>
                                <?php echo $provider['full_name']; ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

            </div>


            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Hourly Price (₹) *</label>

                    <input type="number"
                           class="form-control"
                           name="price"
                           value="<?php echo $service['price']; ?>"
                           min="0"
                           step="0.01"
                           required>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">Status *</label>

                    <select class="form-select" name="service_status">

                        <option value="Active"
                            <?php
                            if($service['service_status'] == "Active")
                            {
                                echo "selected";
                            }
                            ?>>
                            Active
                        </option>

                        <option value="Inactive"
                            <?php
                            if($service['service_status'] == "Inactive")
                            {
                                echo "selected";
                            }
                            ?>>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>


            <div class="mb-3">

                <label class="form-label">Description *</label>

                <textarea class="form-control"
                          name="description"
                          rows="5"
                          required><?php echo $service['description']; ?></textarea>

            </div>


            <div class="mb-3">

                <label class="form-label">Service Image</label>

                <input type="file"
                       class="form-control"
                       name="service_image"
                       accept=".jpg,.jpeg,.png,.webp">

                <div class="form-text">
                    Leave empty to keep the existing image.
                </div>

            </div>


            <?php if($service['service_image'] != "") { ?>

                <div class="mb-3">

                    <img src="../../../assets/services/<?php echo $service['service_image']; ?>"
                         class="img-thumbnail"
                         style="max-height:150px;">

                </div>

            <?php } ?>


            <hr>

            <div class="d-flex justify-content-between">

                <a href="services.php" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit"
                        name="update"
                        class="btn btn-primary">
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