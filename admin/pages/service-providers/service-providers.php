<?php

$pageTitle = "Service Providers";
$pageCss = "service-providers.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$q = "select * from service_providers order by provider_id desc";

$res = mysqli_query($conn, $q);

if (!$res)
{
    die("Provider query failed.");
}


$totalProviders = mysqli_num_rows($res);


ob_start();

?>


<div class="providers-page">


    <div class="providers-header">


        <div>

            <h1>
                Service Providers
            </h1>

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

            if ($_GET["success"] == "provider_added")
            {
                echo "Provider added successfully.";
            }
            elseif ($_GET["success"] == "provider_updated")
            {
                echo "Provider updated successfully.";
            }
            elseif ($_GET["success"] == "provider_deleted")
            {
                echo "Provider deleted successfully.";
            }

            ?>


        </div>


    <?php endif; ?>


    <?php if (isset($_GET["error"])): ?>


        <div class="provider-alert error">


            <?php

            if ($_GET["error"] == "invalid_provider")
            {
                echo "Invalid service provider.";
            }
            elseif ($_GET["error"] == "provider_not_found")
            {
                echo "Service provider not found.";
            }
            elseif ($_GET["error"] == "provider_delete_failed")
            {
                echo "Unable to delete service provider.";
            }
            elseif ($_GET["error"] == "provider_has_services")
            {
                echo "This provider cannot be deleted because services are assigned to this provider.";
            }
            else
            {
                echo "Something went wrong.";
            }

            ?>


        </div>


    <?php endif; ?>


    <div class="providers-card">


        <div class="providers-card-header">


            <div>

                <h2>
                    All Service Providers
                </h2>

                <p>
                    View and manage all registered service providers.
                </p>

            </div>


            <span class="provider-count">

                <?php echo $totalProviders; ?>

                Providers

            </span>


        </div>


        <!-- Search -->

        <div class="admin-search">

            <input
                type="text"
                id="providerSearch"
                placeholder="Search service providers..."
            >

        </div>


        <?php if ($totalProviders == 0): ?>


            <div class="providers-empty">


                <div class="empty-icon">
                    +
                </div>


                <h3>
                    No Service Providers Found
                </h3>


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

                            <th>
                                Sr.
                            </th>

                            <th>
                                Provider
                            </th>

                            <th>
                                Contact
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Experience
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Availability
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

                        while ($provider = mysqli_fetch_array($res)):


                            /*
                             * Get category name separately.
                             */

                            $categoryId = $provider["category_id"];

                            $q2 = "select * from categories
                                   where category_id = $categoryId";

                            $categoryResult =
                                mysqli_query($conn, $q2);

                            $category =
                                mysqli_fetch_array($categoryResult);

                        ?>


                            <tr class="provider-search-row">


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

                                                if ($provider["gender"] != "")
                                                {
                                                    echo htmlspecialchars(
                                                        $provider["gender"]
                                                    );
                                                }
                                                else
                                                {
                                                    echo "Provider";
                                                }

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


                                    <?php if (!empty($category["category_name"])): ?>


                                        <span class="category-badge">


                                            <?php

                                            echo htmlspecialchars(
                                                $category["category_name"]
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


                                    <?php if ($provider["experience"] != ""): ?>


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


                <!-- No Search Result -->

                <div
                    id="providerNoResult"
                    class="admin-no-result"
                >
                    No service providers found.
                </div>


            </div>


        <?php endif; ?>


    </div>


</div>


<!-- Provider Search -->

<script>

var searchInput =
    document.getElementById("providerSearch");

var rows =
    document.querySelectorAll(".provider-search-row");

var noResult =
    document.getElementById("providerNoResult");


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
var providerTableContainer =
    document.querySelector(".providers-table-container");

if (providerTableContainer)
{

    var isDragging = false;
    var startX = 0;
    var scrollLeft = 0;


    providerTableContainer.addEventListener(
        "mousedown",
        function(e)
        {

            isDragging = true;

            providerTableContainer.classList.add(
                "dragging"
            );

            startX =
                e.pageX -
                providerTableContainer.offsetLeft;

            scrollLeft =
                providerTableContainer.scrollLeft;

        }
    );


    providerTableContainer.addEventListener(
        "mousemove",
        function(e)
        {

            if (!isDragging)
            {
                return;
            }


            e.preventDefault();


            var x =
                e.pageX -
                providerTableContainer.offsetLeft;


            var distance =
                (x - startX) * 1.5;


            providerTableContainer.scrollLeft =
                scrollLeft - distance;

        }
    );


    providerTableContainer.addEventListener(
        "mouseup",
        function()
        {

            isDragging = false;

            providerTableContainer.classList.remove(
                "dragging"
            );

        }
    );


    providerTableContainer.addEventListener(
        "mouseleave",
        function()
        {

            isDragging = false;

            providerTableContainer.classList.remove(
                "dragging"
            );

        }
    );

}


</script>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>