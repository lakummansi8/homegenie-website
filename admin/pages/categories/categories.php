<?php

$pageTitle = "Categories";
$pageCss = "categories.css";

$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";


/*
|--------------------------------------------------------------------------
| Fetch Categories
|--------------------------------------------------------------------------
*/

$categories = [];

$query = "
    SELECT
        category_id,
        category_name,
        category_image,
        description,
        category_status,
        created_at
    FROM categories
    ORDER BY category_id DESC
";

$result = $conn->query($query);

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}


/*
|--------------------------------------------------------------------------
| Page Content
|--------------------------------------------------------------------------
*/

ob_start();

?>

<div class="categories-page">


    <!-- Page Header -->

    <div class="categories-header">

        <div>

            <h2>Categories</h2>

            <p>
                Manage the service categories available on HomeGenie.
            </p>

        </div>


        <button
            type="button"
            class="btn btn-primary"
            id="addCategoryBtn"
        >
            + Add Category
        </button>

    </div>



    <!-- Categories Table Card -->

    <div class="categories-card">

        <div class="categories-card-header">

            <div>

                <h3>All Categories</h3>

                <p>
                    View and manage your service categories.
                </p>

            </div>

        </div>



        <?php if (empty($categories)): ?>

            <!-- Empty State -->

            <div class="categories-empty">

                <h4>No categories found</h4>

                <p>
                    There are currently no categories available.
                </p>

            </div>


        <?php else: ?>

            <!-- Categories Table -->

            <div class="table-responsive">

                <table class="table categories-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Category</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th colspan=2 style="text-align:center;">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($categories as $category): ?>

                            <tr>

                                <td>
                                    <?= (int) $category["category_id"] ?>
                                </td>


                                <td>

                                    <div class="category-name">

                                        <?php if (!empty($category["category_image"])): ?>

                                            <img
                                                src="<?= $assetPath ?>assets/categories/<?= htmlspecialchars($category["category_image"]) ?>"
                                                alt="<?= htmlspecialchars($category["category_name"]) ?>"
                                                class="category-image"
                                            >

                                        <?php endif; ?>


                                        <strong>
                                            <?= htmlspecialchars($category["category_name"]) ?>
                                        </strong>

                                    </div>

                                </td>


                                <td>

                                    <?php if (!empty($category["description"])): ?>

                                        <?= htmlspecialchars($category["description"]) ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            No description
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ($category["category_status"] === "Active"): ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?= htmlspecialchars($category["created_at"]) ?>
                                </td>


                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Edit
                                        </button>

                                    </td>
                                        <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Delete
                                        </button>

                                    </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>



<!-- Add Category Modal -->

<div
    class="category-modal"
    id="addCategoryModal"
>

    <div class="category-modal-overlay"></div>


    <div class="category-modal-content">


        <!-- Modal Header -->

        <div class="category-modal-header">

            <div>

                <h3>Add Category</h3>

                <p>
                    Create a new service category.
                </p>

            </div>


            <button
                type="button"
                class="category-modal-close"
                id="closeCategoryModal"
            >
                &times;
            </button>

        </div>



        <!-- Add Category Form -->

        <form
            id="addCategoryForm"
            method="POST"
            action="add-category.php"
            enctype="multipart/form-data"
        >


            <!-- Category Name -->

            <div class="form-group">

                <label for="categoryName">
                    Category Name
                </label>

                <input
                    type="text"
                    id="categoryName"
                    name="category_name"
                    placeholder="Enter category name"
                    required
                >

            </div>



            <!-- Category Image -->

            <div class="form-group">

                <label for="categoryImage">
                    Category Image
                </label>

                <input
                    type="file"
                    id="categoryImage"
                    name="category_image"
                    accept="image/png, image/jpeg, image/webp"
                >

                <small class="form-help">
                    JPG, PNG or WEBP. Maximum size: 2 MB.
                </small>

            </div>



            <!-- Description -->

            <div class="form-group">

                <label for="categoryDescription">
                    Description
                </label>

                <textarea
                    id="categoryDescription"
                    name="description"
                    rows="4"
                    placeholder="Enter category description"
                ></textarea>

            </div>



            <!-- Status -->

            <div class="form-group">

                <label for="categoryStatus">
                    Status
                </label>

                <select
                    id="categoryStatus"
                    name="category_status"
                >

                    <option value="Active">
                        Active
                    </option>

                    <option value="Inactive">
                        Inactive
                    </option>

                </select>

            </div>



            <!-- Modal Actions -->

            <div class="category-modal-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="cancelCategoryBtn"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Category
                </button>

            </div>

        </form>

    </div>

</div>



<script>

    const addCategoryBtn =
        document.getElementById("addCategoryBtn");

    const addCategoryModal =
        document.getElementById("addCategoryModal");

    const closeCategoryModal =
        document.getElementById("closeCategoryModal");

    const cancelCategoryBtn =
        document.getElementById("cancelCategoryBtn");

    const categoryModalOverlay =
        document.querySelector(".category-modal-overlay");


    function openCategoryModal() {

        addCategoryModal.classList.add("active");

    }


    function closeCategoryModalHandler() {

        addCategoryModal.classList.remove("active");

    }


    addCategoryBtn.addEventListener(
        "click",
        openCategoryModal
    );


    closeCategoryModal.addEventListener(
        "click",
        closeCategoryModalHandler
    );


    cancelCategoryBtn.addEventListener(
        "click",
        closeCategoryModalHandler
    );


    categoryModalOverlay.addEventListener(
        "click",
        closeCategoryModalHandler
    );

</script>


<?php

$pageContent = ob_get_clean();


/*
|--------------------------------------------------------------------------
| Load Admin Layout
|--------------------------------------------------------------------------
*/

require_once "../../layout/admin-layout.php";