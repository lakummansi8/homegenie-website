<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST["full_name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $experience = trim($_POST["experience"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $area = trim($_POST["area"] ?? "");
    $city = trim($_POST["city"] ?? "");
    $availability = trim($_POST["availability"] ?? "");

    if (
        $fullName === "" ||
        $phone === "" ||
        $gender === "" ||
        $experience === "" ||
        $address === "" ||
        $area === "" ||
        $city === "" ||
        $availability === ""
    ) {
        $error = "Please fill in all fields.";
    } else {

        $stmt = $conn->prepare(
            "UPDATE service_providers
             SET
                full_name = ?,
                phone = ?,
                gender = ?,
                experience = ?,
                address = ?,
                area = ?,
                city = ?,
                availability = ?
             WHERE provider_id = ?"
        );

        $stmt->bind_param(
            "ssssssssi",
            $fullName,
            $phone,
            $gender,
            $experience,
            $address,
            $area,
            $city,
            $availability,
            $providerId
        );

        if ($stmt->execute()) {
            $_SESSION["provider_name"] = $fullName;
            $success = "Profile updated successfully.";
        } else {
            $error = "Failed to update profile.";
        }

        $stmt->close();
    }
}

$stmt = $conn->prepare(
    "SELECT
        sp.full_name,
        sp.email,
        sp.phone,
        sp.gender,
        sp.experience,
        sp.address,
        sp.area,
        sp.city,
        sp.availability,
        sp.account_status,
        c.category_name
     FROM service_providers sp
     LEFT JOIN categories c
        ON sp.category_id = c.category_id
     WHERE sp.provider_id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $providerId);
$stmt->execute();

$result = $stmt->get_result();
$provider = $result->fetch_assoc();

$stmt->close();

$pageTitle = "My Profile";
$pageCss = "myprofile.css";

require_once "layout/provider-layout.php";
?>

<div class="myprofile-page">

    <div class="profile-header">
        <div>
            <h2>My Profile</h2>
            <p>Update your personal and service information.</p>
        </div>
    </div>


    <?php if (isset($success)): ?>

        <div class="profile-message success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>

    <?php endif; ?>


    <?php if (isset($error)): ?>

        <div class="profile-message error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <form method="POST" action="myprofile.php">

        <div class="profile-section">

            <div class="profile-section-header">
                <h3>Personal Information</h3>
            </div>

            <div class="profile-form-grid">

                <div class="form-group">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="full_name"
                        value="<?php echo htmlspecialchars($provider["full_name"]); ?>"
                        required
                    >
                </div>


                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        value="<?php echo htmlspecialchars($provider["email"]); ?>"
                        readonly
                    >
                </div>


                <div class="form-group">
                    <label>Phone</label>
                    <input
                        type="text"
                        name="phone"
                        value="<?php echo htmlspecialchars($provider["phone"]); ?>"
                        required
                    >
                </div>


                <div class="form-group">
                    <label>Gender</label>

                    <select name="gender" required>

                        <option value="">Select Gender</option>

                        <option
                            value="Male"
                            <?php echo $provider["gender"] === "Male" ? "selected" : ""; ?>
                        >
                            Male
                        </option>

                        <option
                            value="Female"
                            <?php echo $provider["gender"] === "Female" ? "selected" : ""; ?>
                        >
                            Female
                        </option>

                        <option
                            value="Other"
                            <?php echo $provider["gender"] === "Other" ? "selected" : ""; ?>
                        >
                            Other
                        </option>

                    </select>
                </div>


                <div class="form-group">
                    <label>Experience</label>
                    <input
                        type="text"
                        name="experience"
                        value="<?php echo htmlspecialchars($provider["experience"]); ?>"
                        required
                    >
                </div>


                <div class="form-group">
                    <label>Account Status</label>
                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($provider["account_status"]); ?>"
                        readonly
                    >
                </div>

            </div>

        </div>


        <div class="profile-section">

            <div class="profile-section-header">
                <h3>Service Information</h3>
            </div>

            <div class="profile-form-grid">

                <div class="form-group">
                    <label>Category</label>
                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($provider["category_name"] ?? "Not Assigned"); ?>"
                        readonly
                    >
                </div>


                <div class="form-group">
                    <label>Availability</label>

                    <select name="availability" required>

                        <option value="">Select Availability</option>

                        <option
                            value="Available"
                            <?php echo $provider["availability"] === "Available" ? "selected" : ""; ?>
                        >
                            Available
                        </option>

                        <option
                            value="Not Available"
                            <?php echo $provider["availability"] === "Not Available" ? "selected" : ""; ?>
                        >
                            Not Available
                        </option>

                    </select>

                </div>

            </div>

        </div>


        <div class="profile-section">

            <div class="profile-section-header">
                <h3>Address Information</h3>
            </div>

            <div class="profile-form-grid">

                <div class="form-group full-width">
                    <label>Address</label>

                    <textarea
                        name="address"
                        rows="3"
                        required
                    ><?php echo htmlspecialchars($provider["address"]); ?></textarea>

                </div>


                <div class="form-group">
                    <label>Area</label>

                    <input
                        type="text"
                        name="area"
                        value="<?php echo htmlspecialchars($provider["area"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">
                    <label>City</label>

                    <input
                        type="text"
                        name="city"
                        value="<?php echo htmlspecialchars($provider["city"]); ?>"
                        required
                    >

                </div>

            </div>

        </div>


        <div class="profile-actions">

            <button type="submit" class="save-button">
                Save Changes
            </button>

        </div>

    </form>

</div>

</section>
</main>
</div>
</body>
</html>