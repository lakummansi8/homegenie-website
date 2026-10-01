```php id="xq8b0f"
<?php

require_once "../auth/provider-auth-check.php";
require_once "../config/db.php";

$providerId = $_SESSION["provider_id"];


/* Get services of this provider */

$sql = "SELECT * FROM services
        WHERE provider_id = $providerId
        ORDER BY service_id DESC";

$result = mysqli_query($conn, $sql);


$pageTitle = "My Services";
$pageCss = "services.css";

require_once "layout/provider-layout.php";
?>

<div class="services-page">

    <div class="services-header">

        <div>
            <h2>My Services</h2>
            <p>Manage the services you provide to customers.</p>
        </div>

    </div>


    <?php if (isset($_GET["updated"])): ?>

        <div class="service-message success-message">
            Service updated successfully.
        </div>

    <?php endif; ?>


    <?php if (isset($_GET["deleted"])): ?>

        <div class="service-message success-message">
            Service deleted successfully.
        </div>

    <?php endif; ?>


    <div class="services-section">

        <?php if (mysqli_num_rows($result) > 0): ?>

            <div class="services-table-wrapper">

                <table class="services-table">

                    <thead>

                        <tr>
                            <th>Service</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($service = mysqli_fetch_assoc($result)): ?>

                        <?php

                        /* Get category name */

                        $categoryId = $service["category_id"];

                        $categorySql = "SELECT category_name
                                        FROM categories
                                        WHERE category_id = $categoryId";

                        $categoryResult = mysqli_query($conn, $categorySql);

                        $category = mysqli_fetch_assoc($categoryResult);

                        if ($category) {
                            $categoryName = $category["category_name"];
                        } else {
                            $categoryName = "Not Assigned";
                        }

                        ?>

                        <tr>

                            <td>
                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $service["service_name"]
                                    );
                                    ?>
                                </strong>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($categoryName);
                                ?>
                            </td>

                            <td class="description-cell">
                                <?php
                                echo htmlspecialchars(
                                    $service["description"]
                                );
                                ?>
                            </td>

                            <td>
                                ₹<?php
                                echo htmlspecialchars(
                                    $service["price"]
                                );
                                ?>
                            </td>

                            <td>

                                <?php
                                $status = $service["service_status"];
                                ?>

                                <span
                                    class="service-status <?php echo strtolower($status); ?>"
                                >
                                    <?php
                                    echo htmlspecialchars($status);
                                    ?>
                                </span>

                            </td>

                            <td>

                                <div class="service-actions">

                                    <a
                                        href="edit-service.php?id=<?php echo $service["service_id"]; ?>"
                                        class="edit-button"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete-service.php?id=<?php echo $service["service_id"]; ?>"
                                        class="delete-button"
                                        onclick="return confirm('Are you sure you want to delete this service?');"
                                    >
                                        Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="no-services">

                <h3>No Services Found</h3>

                <p>
                    You do not have any services assigned yet.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</section>
</main>
</div>
</body>
</html>

