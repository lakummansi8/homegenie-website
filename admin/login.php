<?php
session_start();

require_once "../php/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter both email and password.";
    } else {

        $sql = "SELECT admin_id, full_name, email, password, account_status
                FROM admins
                WHERE email = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $admin = $result->fetch_assoc();

                if ($admin["account_status"] !== "active") {

                    $error = "Your admin account is inactive.";

                } elseif (password_verify($password, $admin["password"])) {

                    session_regenerate_id(true);

                    $_SESSION["admin_id"] = $admin["admin_id"];
                    $_SESSION["admin_name"] = $admin["full_name"];
                    $_SESSION["admin_email"] = $admin["email"];
                    $_SESSION["admin_logged_in"] = true;

                    header("Location: dashboard.php");
                    exit;

                } else {

                    $error = "Invalid email or password.";

                }

            } else {

                $error = "Invalid email or password.";
            }

            $stmt->close();

        } else {

            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | HomeGenie</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow-sm" style="width: 100%; max-width: 420px;">

        <div class="card-body p-4">

            <div class="text-center mb-4">

                <h2 class="fw-bold">HomeGenie</h2>

                <p class="text-muted mb-0">
                    Admin Login
                </p>

            </div>

            <?php if ($error !== ""): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="">

                <div class="mb-3">

                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter admin email"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Enter admin password"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>