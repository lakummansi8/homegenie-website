<?php
$pageTitle = "Edit Booking";
$assetPath = "../../../";
$adminPath = "../../";
require_once "../../../config/db.php";

$id = $_GET['id'];

if($id == "")
{
    header("Location: bookings.php?error=invalid_booking");
    exit;
}

$q = "select * from bookings where booking_id = $id";
$res = mysqli_query($conn,$q);
$booking = mysqli_fetch_array($res);

if(!$booking)
{
    header("Location: bookings.php?error=booking_not_found");
    exit;
}

$q1 = "select * from users where account_status = 'active'";
$customers = mysqli_query($conn,$q1);

$q2 = "select * from service_providers where account_status = 'active'";
$providers = mysqli_query($conn,$q2);

$q3 = "select * from services where service_status = 'active'";
$services = mysqli_query($conn,$q3);


if(isset($_POST['submit']))
{
    $userId = $_POST['user_id'];
    $providerId = $_POST['provider_id'];
    $serviceId = $_POST['service_id'];
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];
    $bookingAddress = $_POST['booking_address'];
    $bookingStatus = $_POST['booking_status'];

    $q = "update bookings set
          user_id = '$userId',
          provider_id = '$providerId',
          service_id = '$serviceId',
          booking_date = '$bookingDate',
          booking_time = '$bookingTime',
          booking_address = '$bookingAddress',
          booking_status = '$bookingStatus'
          where booking_id = $id";

    $res = mysqli_query($conn,$q);

    if($res)
    {
        header("Location: bookings.php?success=booking_updated");
        exit;
    }
}

ob_start();
?>

<div class="admin-page-header"> 
    <div class="header-title"> 
        <h3>Edit Booking</h3> 
        <p class="text-muted">Update booking details.</p> 
    </div> 
    <div class="header-actions"> 
        <a href="bookings.php" class="btn btn-secondary">← Back to Bookings</a> 
    </div> 
</div> 
 
<div class="card"> 
    <div class="card-header bg-dark text-white"> 
        <strong>Booking Details</strong> 
    </div> 
 
    <div class="card-body"> 
        <form method="POST"> 
 
            <div class="row"> 
 
                <div class="col-md-6 mb-3"> 
                    <label class="form-label">Customer</label> 
                    <select class="form-select" name="user_id" required> 
                        <?php while ($customer = mysqli_fetch_array($customers)) { ?> 
                            <option value="<?php print $customer["user_id"]; ?>" 
                                <?php if($customer["user_id"] == $booking["user_id"]) print "selected"; ?>> 
                                <?php print $customer["full_name"]; ?> 
                                - <?php print $customer["email"]; ?> 
                            </option> 
                        <?php } ?> 
                    </select> 
                </div> 
 
                <div class="col-md-6 mb-3"> 
                    <label class="form-label">Service Provider</label> 
                    <select class="form-select" name="provider_id" required> 
                        <?php while ($provider = mysqli_fetch_array($providers)) { ?> 
                            <option value="<?php print $provider["provider_id"]; ?>" 
                                <?php if($provider["provider_id"] == $booking["provider_id"]) print "selected"; ?>> 
                                <?php print $provider["full_name"]; ?> 
                            </option> 
                        <?php } ?> 
                    </select> 
                </div> 
 
            </div> 
 
            <div class="row"> 
 
                <div class="col-md-6 mb-3"> 
                    <label class="form-label">Service</label> 
                    <select class="form-select" name="service_id" required> 
                        <?php while ($service = mysqli_fetch_array($services)) { ?> 
                            <option value="<?php print $service["service_id"]; ?>" 
                                <?php if($service["service_id"] == $booking["service_id"]) print "selected"; ?>> 
                                <?php print $service["service_name"]; ?> 
                            </option> 
                        <?php } ?> 
                    </select> 
                </div> 
 
                <div class="col-md-6 mb-3"> 
                    <label class="form-label">Booking Status</label> 
                    <select class="form-select" name="booking_status" required> 
                        <option value="Pending" <?php if($booking["booking_status"] == "Pending") print "selected"; ?>>Pending</option> 
                        <option value="Accepted" <?php if($booking["booking_status"] == "Accepted") print "selected"; ?>>Accepted</option> 
                        <option value="Completed" <?php if($booking["booking_status"] == "Completed") print "selected"; ?>>Completed</option> 
                        <option value="Cancelled" <?php if($booking["booking_status"] == "Cancelled") print "selected"; ?>>Cancelled</option> 
                    </select> 
                </div> 
 
            </div> 
 
            <div class="row"> 
 
                <div class="col-md-6 mb-3"> 
                    <label class="form-label">Booking Date</label> 
                    <input type="date" class="form-control" 
                           name="booking_date" 
                           value="<?php print $booking["booking_date"]; ?>" 
                           required> 
                </div> 
 
                <div class="col-md-6 mb-3"> 
                    <label class="form-label">Booking Time</label> 
                    <input type="time" class="form-control" 
                           name="booking_time" 
                           value="<?php print $booking["booking_time"]; ?>" 
                           required> 
                </div> 
 
            </div> 
 
            <div class="mb-3"> 
                <label class="form-label">Booking Address</label> 
                <textarea class="form-control" 
                          name="booking_address" 
                          rows="4" 
                          required><?php print $booking["booking_address"]; ?></textarea> 
            </div> 
 
            <hr> 
 
            <a href="bookings.php" class="btn btn-secondary">Cancel</a> 
            <button type="submit" name="submit" class="btn btn-primary">Update Booking</button> 
 
        </form> 
    </div> 
</div> 
 
<?php 
$pageContent = ob_get_clean(); 
require_once "../../layout/admin-layout.php"; 
?>