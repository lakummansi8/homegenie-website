<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$userId = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT full_name, email, phone, address, city
     FROM users
     WHERE user_id = ?"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$pageTitle = "My Profile";

require_once "layout/customer-layout.php";

?>

<div class="welcome">

    <h1>My Profile</h1>

    <p>
        Update your personal account information.
    </p>

</div>


<?php if (isset($_GET["updated"])): ?>

    <div class="success-message">
        Profile updated successfully!
    </div>

<?php endif; ?>


<div class="profile-section">

    <h2>Personal Information</h2>

    <form action="update-profile.php" method="POST">

        <div class="profile-grid">

            <div class="profile-item">

                <label>Full Name</label>

                <input
                    type="text"
                    name="full_name"
                    value="<?php echo htmlspecialchars($user["full_name"]); ?>"
                    required
                >

            </div>


            <div class="profile-item">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($user["email"]); ?>"
                    required
                >

            </div>


            <div class="profile-item">

                <label>Phone</label>

                <input
                    type="text"
                    name="phone"
                    value="<?php echo htmlspecialchars($user["phone"]); ?>"
                    required
                >

            </div>


            <div class="profile-item">

                <label>City</label>

                <input
                    type="text"
                    name="city"
                    value="<?php echo htmlspecialchars($user["city"]); ?>"
                    required
                >

            </div>


            <div class="profile-item profile-address">

                <label>Address</label>

                <textarea
                    name="address"
                    required
                ><?php echo htmlspecialchars($user["address"]); ?></textarea>

            </div>

        </div>


        <div class="form-actions">

            <button type="submit">
                Save Changes
            </button>

        </div>

    </form>

</div>


</div>
</main>

</div>

</body>
</html>