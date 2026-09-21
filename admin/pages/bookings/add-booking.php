<?php
$pageTitle = "Add Booking";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$userId = "";
$providerId = "";
$serviceId = "";
$bookingDate = "";
$bookingTime = "";
$bookingAddress = "";
$bookingStatus = "pending";
$error = "";

if(isset($_POST['submit']))
{
    $userId = $_POST['user_id'];
    $providerId = $_POST['provider_id'];
    $serviceId = $_POST['service_id'];
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];
    $bookingAddress = $_POST['booking_address'];
    $bookingStatus = $_POST['booking_status'];

    if($userId == "")
        $error = "Please select a customer.";

    elseif($providerId == "")
        $error = "Please select a service provider.";

    elseif($serviceId == "")
        $error = "Please select a service.";

    elseif($bookingDate == "")
        $error = "Please select a booking date.";

    elseif($bookingTime == "")
        $error = "Please select a booking time.";

    elseif($bookingAddress == "")
        $error = "Please enter the booking address.";

    else
    {
        $q = "select * from users where user_id = $userId";
        $res = mysqli_query($conn,$q);

        if(mysqli_num_rows($res) == 0)
        {
            $error = "Customer not found.";
        }
    }

    if($error == "")
    {
        $q = "select * from service_providers where provider_id = $providerId";
        $res = mysqli_query($conn,$q);

        if(mysqli_num_rows($res) == 0)
        {
            $error = "Service provider not found.";
        }
    }

    if($error == "")
    {
        $q = "select * from services where service_id = $serviceId and provider_id = $providerId";
        $res = mysqli_query($conn,$q);

        if(mysqli_num_rows($res) == 0)
        {
            $error = "Selected service does not belong to this provider.";
        }
    }

    if($error == "")
    {
        $q = "insert into bookings(user_id,provider_id,service_id,booking_date,booking_time,booking_address,booking_status)
              values('$userId','$providerId','$serviceId','$bookingDate','$bookingTime','$bookingAddress','$bookingStatus')";

        $res = mysqli_query($conn,$q);

        if($res)
        {
            header("Location: bookings.php?success=booking_added");
            exit;
        }
        else
        {
            $error = "Unable to create booking.";
        }
    }
}


$q1 = "select * from users where account_status = 'active'";
$res1 = mysqli_query($conn,$q1);


$q2 = "select * from service_providers where account_status = 'active'";
$res2 = mysqli_query($conn,$q2);


$q3 = "select * from services where service_status = 'active'";
$res3 = mysqli_query($conn,$q3);

ob_start();
?>

<div class="admin-page-header">
    <div class="header-title">
        <h3>Add Booking</h3>
        <p class="text-muted">Create a new customer service booking.</p>
    </div>
    <div class="header-actions">
        <a href="bookings.php" class="btn btn-secondary">&larr; Back to Bookings</a>
    </div>
</div>

<?php if ($error != ""): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            <li><?php print $error; ?></li>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white">
        <strong>Booking Details</strong>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer *</label>
                    <select class="form-select" name="user_id" required>
                        <option value="">Select Customer</option>

                        <?php while($user = mysqli_fetch_array($res1)) { ?>

                            <option value="<?php print $user['user_id']; ?>"
                                <?php if($userId == $user['user_id']) print "selected"; ?>>

                                <?php print $user['full_name']; ?>
                                - <?php print $user['email']; ?>

                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Provider *</label>

                    <select class="form-select" name="provider_id" id="provider_id" required>

                        <option value="">Select Provider</option>

                        <?php while($provider = mysqli_fetch_array($res2)) { ?>

                            <option value="<?php print $provider['provider_id']; ?>"
                                <?php if($providerId == $provider['provider_id']) print "selected"; ?>>

                                <?php print $provider['full_name']; ?>

                            </option>

                        <?php } ?>

                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service *</label>

                    <select class="form-select" name="service_id" id="service_id" required>

                        <option value="">Select Service</option>

                        <?php while($service = mysqli_fetch_array($res3)) { ?>

                            <option value="<?php print $service['service_id']; ?>"
                                data-provider="<?php print $service['provider_id']; ?>"
                                <?php if($serviceId == $service['service_id']) print "selected"; ?>>

                                <?php print $service['service_name']; ?>

                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Status *</label>

                    <select class="form-select" name="booking_status" required>

                        <option value="pending"
                            <?php if($bookingStatus == "pending") print "selected"; ?>>
                            Pending
                        </option>

                        <option value="confirmed"
                            <?php if($bookingStatus == "confirmed") print "selected"; ?>>
                            Confirmed
                        </option>

                        <option value="completed"
                            <?php if($bookingStatus == "completed") print "selected"; ?>>
                            Completed
                        </option>

                        <option value="cancelled"
                            <?php if($bookingStatus == "cancelled") print "selected"; ?>>
                            Cancelled
                        </option>

                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Date *</label>

                    <input type="date"
                           class="form-control"
                           name="booking_date"
                           value="<?php print $bookingDate; ?>"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Time *</label>

                    <input type="time"
                           class="form-control"
                           name="booking_time"
                           value="<?php print $bookingTime; ?>"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Address *</label>

                <textarea class="form-control"
                          name="booking_address"
                          rows="4"
                          required><?php print $bookingAddress; ?></textarea>
            </div>

            <hr>

            <div class="admin-form-actions">
                <a href="bookings.php" class="btn btn-secondary">Cancel</a>

                <button type="submit"
                        name="submit"
                        class="btn btn-primary">
                    Create Booking
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const providerSelect = document.getElementById("provider_id");
    const serviceSelect = document.getElementById("service_id");

    function filterServices() {
        const selectedProvider = providerSelect.value;
        const options = serviceSelect.querySelectorAll("option[data-provider]");

        options.forEach(function (option) {
            if (selectedProvider === "" || option.dataset.provider === selectedProvider) {
                option.hidden = false;
            } else {
                option.hidden = true;
            }
        });

        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.provider && selectedOption.dataset.provider !== selectedProvider) {
            serviceSelect.value = "";
        }
    }

    providerSelect.addEventListener("change", filterServices);
    filterServices();
});
</script>

<?php
$pageContent = ob_get_clean();
require_once "../../layout/admin-layout.php";
?>
