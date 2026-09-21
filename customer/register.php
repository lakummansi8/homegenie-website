<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Registration - HomeGenie</title>

    <link rel="stylesheet" href="customer.css?v=10">

</head>

<body>

<div class="customer-register-page">

    <div class="customer-register-card">

        <a href="../index.php" class="customer-register-back">
            ← Back to Home
        </a>

        <div class="customer-register-heading">

            <h1>HomeGenie</h1>
            <p>Create your customer account</p>

        </div>

        <form action="register-process.php" method="POST">

            <div class="customer-register-row">

                <div class="customer-register-field">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="full_name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>

                <div class="customer-register-field">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>

            </div>

            <div class="customer-register-row">

                <div class="customer-register-field">

                    <label>Phone</label>

                    <input
                        type="text"
                        name="phone"
                        placeholder="Enter your phone number"
                        required
                    >

                </div>

                <div class="customer-register-field">

                    <label>City</label>

                    <input
                        type="text"
                        name="city"
                        placeholder="Enter your city"
                        required
                    >

                </div>

            </div>

            <div class="customer-register-field">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

            </div>

            <div class="customer-register-field">

                <label>Address</label>

                <textarea
                    name="address"
                    placeholder="Enter your complete address"
                    required
                ></textarea>

            </div>

            <button
                type="submit"
                name="register"
                class="customer-register-button"
            >
                Create Account
            </button>

        </form>

        <div class="customer-register-login">

            Already have an account?

            <a href="../auth/login.php">
                Sign In
            </a>

        </div>

    </div>

</div>

</body>

</html>