<?php

$pageTitle = "Services";
$pageCss = "services.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$q = "select * from services";

$res = mysqli_query($conn, $q);

$totalServices = mysqli_num_rows($res);


ob_start();

?>


<div class="admin-page-header">

    <div class="header-title">

        <h3>
            Services
        </h3>

        <p class="text-muted">
            Manage the services available on HomeGenie.
        </p>

    </div>


    <div class="header-actions">

        <a
            href="add-service.php"
            class="service-btn service-btn-primary"
        >
            + Add Service
        </a>

    </div>

</div>


<?php if (isset($_GET["success"])) { ?>

    <div class="service-alert service-alert-success">

        <?php

        if ($_GET["success"] == "service_added")
        {
            echo "Service added successfully.";
        }
        elseif ($_GET["success"] == "service_updated")
        {
            echo "Service updated successfully.";
        }
        elseif ($_GET["success"] == "service_deleted")
        {
            echo "Service deleted successfully.";
        }

        ?>

    </div>

<?php } ?>


<?php if (isset($_GET["error"])) { ?>

    <div class="service-alert service-alert-danger">

        <?php

        if ($_GET["error"] == "invalid_service")
        {
            echo "Invalid service.";
        }
        elseif ($_GET["error"] == "service_not_found")
        {
            echo "Service not found.";
        }
        else
        {
            echo "Something went wrong.";
        }

        ?>

    </div>

<?php } ?>


<div class="services-card">


    <div class="services-card-header">

        <strong>
            All Services
        </strong>

        <span class="service-count">

            <?php echo $totalServices; ?>

            Services

        </span>

    </div>


    <!-- Search -->

    <div class="admin-search">

        <input
            type="text"
            id="serviceSearch"
            placeholder="Search services..."
        >

    </div>


    <div class="services-card-body">


        <?php if ($totalServices == 0) { ?>


            <div class="no-services">

                <p>
                    No services found. Create your first service to get started.
                </p>

                <a
                    href="add-service.php"
                    class="service-btn service-btn-primary"
                >
                    + Add Service
                </a>

            </div>


        <?php } else { ?>


            <div class="service-table-responsive">


                <table class="services-table">


                    <thead>

                        <tr>

                            <th>
                                Sr. No.
                            </th>

                            <th>
                                Service
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Provider
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Price / Hour
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    $srno = 1;

                    while ($service = mysqli_fetch_array($res))
                    {


                        $categoryId = $service["category_id"];

                        $q1 = "select * from categories
                               where category_id = $categoryId";

                        $res1 = mysqli_query($conn, $q1);

                        $category = mysqli_fetch_array($res1);


                        $providerId = $service["provider_id"];

                        $q2 = "select * from service_providers
                               where provider_id = $providerId";

                        $res2 = mysqli_query($conn, $q2);

                        $provider = mysqli_fetch_array($res2);

                    ?>


                        <tr class="service-search-row">


                            <!-- SERIAL NUMBER -->

                            <td>

                                <?php echo $srno; ?>

                            </td>


                            <!-- SERVICE -->

                            <td>

                                <div class="service-name">


                                    <?php if ($service["service_image"] != "") { ?>


                                        <img
                                            src="../../../assets/services/<?php echo $service["service_image"]; ?>"
                                            alt="<?php echo htmlspecialchars($service["service_name"]); ?>"
                                            class="service-image"
                                        >


                                    <?php } else { ?>


                                        <div class="service-image-placeholder">
                                            -
                                        </div>


                                    <?php } ?>


                                    <div class="service-name-content">

                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $service["service_name"]
                                            );
                                            ?>
                                        </strong>

                                    </div>


                                </div>

                            </td>


                            <!-- CATEGORY -->

                            <td class="service-category">

                                <?php

                                if ($category)
                                {
                                    echo htmlspecialchars(
                                        $category["category_name"]
                                    );
                                }
                                else
                                {
                                    echo "No category";
                                }

                                ?>

                            </td>


                            <!-- PROVIDER -->

                            <td class="service-provider">

                                <?php

                                if ($provider)
                                {
                                    echo htmlspecialchars(
                                        $provider["full_name"]
                                    );
                                }
                                else
                                {
                                    echo "No provider";
                                }

                                ?>

                            </td>


                            <!-- DESCRIPTION -->

                            <td class="service-description">

                                <?php

                                if ($service["description"] != "")
                                {
                                    echo htmlspecialchars(
                                        $service["description"]
                                    );
                                }
                                else
                                {
                                    echo "No description";
                                }

                                ?>

                            </td>


                            <!-- PRICE -->

                            <td>

                                <span class="service-price">

                                    ₹<?php echo number_format($service["price"], 2); ?>

                                    <small>
                                        / hour
                                    </small>

                                </span>

                            </td>


                            <!-- STATUS -->

                            <td>


                                <?php if ($service["service_status"] == "Active") { ?>


                                    <span class="service-status active">
                                        Active
                                    </span>


                                <?php } else { ?>


                                    <span class="service-status inactive">
                                        Inactive
                                    </span>


                                <?php } ?>


                            </td>


                            <!-- CREATED -->

                            <td class="service-date">

                                <?php

                                echo date(
                                    "d M Y",
                                    strtotime($service["created_at"])
                                );

                                ?>

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div class="service-actions">


                                    <a
                                        href="edit-service.php?id=<?php echo $service["service_id"]; ?>"
                                        class="service-btn service-btn-edit"
                                    >
                                        Edit
                                    </a>


                                    <a
                                        href="delete-service.php?id=<?php echo $service["service_id"]; ?>"
                                        class="service-btn service-btn-delete"
                                        onclick="return confirm('Are you sure you want to delete this service?');"
                                    >
                                        Delete
                                    </a>


                                </div>

                            </td>


                        </tr>


                    <?php

                        $srno++;

                    }

                    ?>


                    </tbody>


                </table>


                <!-- No Search Result -->

                <div
                    id="serviceNoResult"
                    class="admin-no-result"
                >
                    No services found.
                </div>


            </div>


        <?php } ?>


    </div>


</div>


<!-- Service Search -->

<script>

var searchInput =
    document.getElementById("serviceSearch");

var rows =
    document.querySelectorAll(".service-search-row");

var noResult =
    document.getElementById("serviceNoResult");


if (searchInput)
{

    searchInput.addEventListener("keyup", function()
    {

        var searchText =
            searchInput.value.toLowerCase();

        var found = false;


        rows.forEach(function(row)
        {

            var rowText =
                row.innerText.toLowerCase();


            if (rowText.includes(searchText))
            {

                row.style.display = "";

                found = true;

            }
            else
            {

                row.style.display = "none";

            }

        });


        if (noResult)
        {

            if (found)
            {
                noResult.style.display = "none";
            }
            else
            {
                noResult.style.display = "block";
            }

        }

    });

}

</script>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>