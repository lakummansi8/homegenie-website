<?php

require_once "config/db.php";

$pageTitle = "Contact Us";
$pageCss = "contact.css";

$message = "";
$messageType = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $messageText = trim($_POST["message"] ?? "");


    if (
        $fullName === "" ||
        $email === "" ||
        $phone === "" ||
        $subject === "" ||
        $messageText === ""
    ) {

        $message = "Please fill in all fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } else {

        $query = "
            INSERT INTO contact
            (
                full_name,
                email,
                phone,
                subject,
                message,
                message_status
            )
            VALUES
            (?, ?, ?, ?, ?, 'Unread')
        ";

        $stmt = $conn->prepare($query);

        $stmt->bind_param(
            "sssss",
            $fullName,
            $email,
            $phone,
            $subject,
            $messageText
        );


        if ($stmt->execute()) {

            $message = "Your message has been sent successfully. We will get back to you soon.";
            $messageType = "success";

        } else {

            $message = "Something went wrong. Please try again.";
            $messageType = "error";

        }

    }

}

?>


<?php include "includes/header.php"; ?>


<main class="contact-page">


    <!-- Hero -->

    <section class="contact-hero">

        <div class="contact-hero-content">

            <span class="section-label">
                GET IN TOUCH
            </span>

            <h1>
                We're Here to
                <span>Help You</span>
            </h1>

            <p>
                Have a question, need help, or want to know more
                about HomeGenie? Send us a message and our team
                will get back to you.
            </p>

        </div>

    </section>


    <!-- Contact Section -->

    <section class="contact-section">

        <div class="contact-container">


            <!-- Contact Information -->

            <div class="contact-info">

                <span class="section-label">
                    CONTACT US
                </span>

                <h2>
                    Let's Talk
                </h2>

                <p>
                    Whether you need help with a service or have a
                    question about HomeGenie, feel free to contact us.
                </p>


                <div class="contact-details">


                    <div class="contact-detail">

                        <div class="contact-icon">
                            @
                        </div>

                        <div>

                            <span>
                                Email
                            </span>

                            <strong>
                                support@homegenie.com
                            </strong>

                        </div>

                    </div>


                    <div class="contact-detail">

                        <div class="contact-icon">
                            #
                        </div>

                        <div>

                            <span>
                                Phone
                            </span>

                            <strong>
                                +91 98765 43210
                            </strong>

                        </div>

                    </div>


                    <div class="contact-detail">

                        <div class="contact-icon">
                            A
                        </div>

                        <div>

                            <span>
                                Location
                            </span>

                            <strong>
                                Gujarat, India
                            </strong>

                        </div>

                    </div>


                </div>

            </div>


            <!-- Contact Form -->

            <div class="contact-form-box">


                <h2>
                    Send Us a Message
                </h2>

                <p>
                    Fill out the form below and we will contact you.
                </p>


                <?php if ($message !== ""): ?>

                    <div class="form-message <?php echo $messageType; ?>">

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    action="contact.php"
                    autocomplete="off"
                >


                    <div class="form-row">


                        <div class="form-group">

                            <label for="full_name">
                                Full Name
                            </label>

                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                placeholder="Enter your name"
                                autocomplete="off"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="email">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Enter your email"
                                autocomplete="off"
                                required
                            >

                        </div>


                    </div>


                    <div class="form-row">


                        <div class="form-group">

                            <label for="phone">
                                Phone
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                placeholder="Enter your phone number"
                                autocomplete="off"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                placeholder="Enter subject"
                                autocomplete="off"
                                required
                            >

                        </div>


                    </div>


                    <div class="form-group">

                        <label for="message">
                            Message
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            placeholder="Write your message here..."
                            autocomplete="off"
                            required
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="contact-submit"
                    >
                        Send Message
                    </button>


                </form>


            </div>


        </div>

    </section>


    <!-- Bottom CTA -->

    <section class="contact-cta">

        <div class="contact-cta-content">

            <h2>
                Need a Home Service?
            </h2>

            <p>
                Find trusted professionals for your home service needs.
            </p>

            <a
                href="services.php"
                class="cta-button"
            >
                Explore Services
            </a>

        </div>

    </section>


</main>


<?php include "includes/footer.php"; ?>