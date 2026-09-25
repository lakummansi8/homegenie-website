<?php

$pageTitle = "Service Providers";
$pageCss = "service-providers.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$q = "
    SELECT
        service_providers.*,
        categories.category_name
    FROM service_providers
    LEFT JOIN categories
        ON service_providers.category_id = categories.category_id
    ORDER BY service_providers.provider_id DESC
";

$res = mysqli_query($conn, $q);

if (!$res) {
    die("Provider query failed: " . mysqli_error($conn));
}

ob_start();

?>

<div class="providers-page">

    <div class="providers-header">

        <div>
            <h1>Service Providers</h1>

            <p>
                Manage the service providers available on HomeGenie.
            </p>
        </div>

        <a
            href="add-service-provider.php"
            class="provider-add-btn"
        >
            + Add Provider
        </a>

    </div>


    <?php if (isset($_GET["success"])): ?>

        <div class="provider-alert success">

            <?php

            if ($_GET["success"] == "provider_added") {
                echo "Provider added successfully.";
            }

            elseif ($_GET["success"] == "provider_updated") {
                echo "Provider updated successfully.";
            }

            elseif ($_GET["success"] == "provider_deleted") {
                echo "Provider deleted successfully.";
            }

            ?>

        </div>

    <?php endif; ?>


    <?php if (isset($_GET["error"])): ?>

        <div class="provider-alert error">

            <?php

            if ($_GET["error"] == "invalid_provider") {
                echo "Invalid service provider.";
            }

            elseif ($_GET["error"] == "provider_not_found") {
                echo "Service provider not found.";
            }

            elseif ($_GET["error"] == "provider_delete_failed") {
                echo "Unable to delete service provider.";
            }

            else {
                echo "Something went wrong.";
            }

            ?>

        </div>

    <?php endif; ?>


    <div class="providers-card">

        <div class="providers-card-header">

            <div>
                <h2>All Service Providers</h2>

                <p>
                    View and manage all registered service providers.
                </p>
            </div>

            <span class="provider-count">
                <?php echo mysqli_num_rows($res); ?> Providers
            </span>

        </div>


        <?php if (mysqli_num_rows($res) == 0): ?>

            <div class="providers-empty">

                <div class="empty-icon">
                    +
                </div>

                <h3>No Service Providers Found</h3>

                <p>
                    Add your first service provider to get started.
                </p>

                <a
                    href="add-service-provider.php"
                    class="provider-add-btn"
                >
                    + Add Provider
                </a>

            </div>

        <?php else: ?>


            <div class="providers-table-container">

                <table class="providers-table">

                    <thead>

                        <tr>

                            <th>Sr.</th>

                            <th>Provider</th>

                            <th>Contact</th>

                            <th>Category</th>

                            <th>Experience</th>

                            <th>Location</th>

                            <th>Availability</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        $srno = 1;

                        while ($provider = mysqli_fetch_assoc($res)):

                        ?>

                            <tr>

                                <td>

                                    <span class="provider-number">
                                        <?php echo $srno; ?>
                                    </span>

                                </td>


                                <td>

                                    <div class="provider-info">

                                        <?php if (!empty($provider["profile_image"])): ?>

                                            <img
                                                src="../../../assets/providers/<?php echo htmlspecialchars($provider["profile_image"]); ?>"
                                                alt="Provider"
                                                class="provider-image"
                                            >

                                        <?php else: ?>

                                            <div class="provider-image placeholder">

                                                <?php
                                                echo strtoupper(
                                                    substr(
                                                        $provider["full_name"],
                                                        0,
                                                        1
                                                    )
                                                );
                                                ?>

                                            </div>

                                        <?php endif; ?>


                                        <div>

                                            <strong>
                                                <?php
                                                echo htmlspecialchars(
                                                    $provider["full_name"]
                                                );
                                                ?>
                                            </strong>

                                            <span>
                                                <?php
                                                echo !empty($provider["gender"])
                                                    ? htmlspecialchars($provider["gender"])
                                                    : "Provider";
                                                ?>
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <div class="provider-contact">

                                        <span>
                                            <?php
                                            echo htmlspecialchars(
                                                $provider["email"]
                                            );
                                            ?>
                                        </span>

                                        <span>
                                            <?php
                                            echo htmlspecialchars(
                                                $provider["phone"]
                                            );
                                            ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <?php if (!empty($provider["category_name"])): ?>

                                        <span class="category-badge">
                                            <?php
                                            echo htmlspecialchars(
                                                $provider["category_name"]
                                            );
                                            ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="no-data">
                                            No Category
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ($provider["experience"] !== ""): ?>

                                        <span class="experience-text">
                                            <?php
                                            echo htmlspecialchars(
                                                $provider["experience"]
                                            );
                                            ?>
                                            years
                                        </span>

                                    <?php else: ?>

                                        <span class="no-data">
                                            Not specified
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <div class="location-info">

                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $provider["area"]
                                            );
                                            ?>
                                        </strong>

                                        <span>
                                            <?php
                                            echo htmlspecialchars(
                                                $provider["city"]
                                            );
                                            ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <?php if ($provider["availability"] == "Available"): ?>

                                        <span class="status-badge available">
                                            Available
                                        </span>

                                    <?php elseif ($provider["availability"] == "Busy"): ?>

                                        <span class="status-badge busy">
                                            Busy
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge offline">
                                            Not Available
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ($provider["account_status"] == "Active"): ?>

                                        <span class="status-badge active">
                                            Active
                                        </span>

                                    <?php elseif ($provider["account_status"] == "Blocked"): ?>

                                        <span class="status-badge blocked">
                                            Blocked
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge pending">
                                            Pending
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="created-date">

                                        <?php
                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $provider["created_at"]
                                            )
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="provider-actions">

                                        <a
                                            href="edit-service-provider.php?id=<?php echo $provider["provider_id"]; ?>"
                                            class="edit-btn"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="delete-service-provider.php?id=<?php echo $provider["provider_id"]; ?>"
                                            class="delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this provider?');"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php

                            $srno++;

                        endwhile;

                        ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>