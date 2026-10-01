<?php

$pageTitle = "Categories";
$pageCss = "categories.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


$q = "select * from categories";

$res = mysqli_query($conn, $q);

$totalCategories = mysqli_num_rows($res);


ob_start();

?>


<div class="admin-page-header">


    <div class="header-title">

        <h3>
            Categories
        </h3>

        <p class="text-muted">
            Manage the service categories available on HomeGenie.
        </p>

    </div>


    <div class="header-actions">

        <a
            href="add-category.php"
            class="category-btn category-btn-primary"
        >
            + Add Category
        </a>

    </div>


</div>


<?php if (isset($_GET["success"])) { ?>

    <div class="category-alert category-alert-success">

        <?php

        if ($_GET["success"] == "category_added")
        {
            echo "Category added successfully.";
        }
        elseif ($_GET["success"] == "category_updated")
        {
            echo "Category updated successfully.";
        }
        elseif ($_GET["success"] == "category_deleted")
        {
            echo "Category deleted successfully.";
        }

        ?>

    </div>

<?php } ?>


<?php if (isset($_GET["error"])) { ?>

    <div class="category-alert category-alert-danger">

        <?php

        if ($_GET["error"] == "invalid_category")
        {
            echo "Invalid category.";
        }
        elseif ($_GET["error"] == "category_not_found")
        {
            echo "Category not found.";
        }
        else
        {
            echo "Something went wrong.";
        }

        ?>

    </div>

<?php } ?>


<div class="categories-card">


    <div class="categories-card-header">

        <strong>
            All Categories
        </strong>

        <span class="category-count">

            <?php echo $totalCategories; ?>

            Categories

        </span>

    </div>


    <!-- Search -->

    <div class="admin-search">

        <input
            type="text"
            id="categorySearch"
            placeholder="Search categories..."
        >

    </div>


    <div class="categories-card-body">


        <?php if ($totalCategories == 0) { ?>


            <div class="no-categories">

                <p>
                    No categories found. Create your first category to get started.
                </p>

                <a
                    href="add-category.php"
                    class="category-btn category-btn-primary"
                >
                    + Add Category
                </a>

            </div>


        <?php } else { ?>


            <div class="category-table-responsive">


                <table class="categories-table">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Description
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

                    while ($category = mysqli_fetch_array($res))
                    {

                    ?>


                        <tr class="category-search-row">


                            <td>

                                <?php echo $srno; ?>

                            </td>


                            <td>

                                <div class="category-info">


                                    <?php if ($category["category_image"] != "") { ?>


                                        <img
                                            src="../../../assets/categories/<?php echo $category["category_image"]; ?>"
                                            alt="<?php echo htmlspecialchars($category["category_name"]); ?>"
                                            class="category-table-image"
                                        >


                                    <?php } else { ?>


                                        <div class="category-image-empty">
                                            -
                                        </div>


                                    <?php } ?>


                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $category["category_name"]
                                        );

                                        ?>

                                    </strong>


                                </div>

                            </td>


                            <td>

                                <?php

                                if ($category["description"] != "")
                                {
                                    echo htmlspecialchars(
                                        $category["description"]
                                    );
                                }
                                else
                                {
                                    echo "-";
                                }

                                ?>

                            </td>


                            <td>


                                <?php if ($category["category_status"] == "Active") { ?>


                                    <span class="category-status active">
                                        Active
                                    </span>


                                <?php } else { ?>


                                    <span class="category-status inactive">
                                        Inactive
                                    </span>


                                <?php } ?>


                            </td>


                            <td>

                                <?php

                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $category["created_at"]
                                    )
                                );

                                ?>

                            </td>


                            <td>

                                <a
                                    href="edit-category.php?id=<?php echo $category["category_id"]; ?>"
                                    class="category-btn category-btn-edit"
                                >
                                    Edit
                                </a>


                                <a
                                    href="delete-category.php?id=<?php echo $category["category_id"]; ?>"
                                    class="category-btn category-btn-delete"
                                    onclick="return confirm('Are you sure you want to delete this category?');"
                                >
                                    Delete
                                </a>

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
                    id="categoryNoResult"
                    class="admin-no-result"
                >
                    No categories found.
                </div>


            </div>


        <?php } ?>


    </div>


</div>


<!-- Category Search -->

<script>

var searchInput =
    document.getElementById("categorySearch");

var rows =
    document.querySelectorAll(".category-search-row");

var noResult =
    document.getElementById("categoryNoResult");


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