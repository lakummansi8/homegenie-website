```php
<?php

$pageTitle = "Service Providers";
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


<div class="row mb-4">

    <div class="col-md-8">

        <h3>Service Providers</h3>

        <p class="text-muted">
            Manage the service providers available on HomeGenie.
        </p>

    </div>


    <div class="col-md-4 text-md-end">

        <a
            href="add-service-provider.php"
            class="btn btn-primary"
        >
            + Add Provider
        </a>

    </div>

</div>


<?php

if (isset($_GET['success'])) {

    if ($_GET['success'] == "provider_added") {

        print "<div class='alert alert-success'>
            Provider added successfully.
        </div>";

    }

    elseif ($_GET['success'] == "provider_updated") {

        print "<div class='alert alert-success'>
            Provider updated successfully.
        </div>";

    }

    elseif ($_GET['success'] == "provider_deleted") {

        print "<div class='alert alert-success'>
            Provider deleted successfully.
        </div>";

    }

}


if (isset($_GET['error'])) {

    if ($_GET['error'] == "invalid_provider") {

        print "<div class='alert alert-danger'>
            Invalid service provider.
        </div>";

    }

    elseif ($_GET['error'] == "provider_not_found") {

        print "<div class='alert alert-danger'>
            Service provider not found.
        </div>";

    }

    elseif ($_GET['error'] == "provider_delete_failed") {

        print "<div class='alert alert-danger'>
            Unable to delete service provider.
        </div>";

    }

    else {

        print "<div class='alert alert-danger'>
            Something went wrong.
        </div>";

    }

}

?>


<div class="card">

    <div class="card-header bg-dark text-white">

        <strong>
            All Service Providers
        </strong>

    </div>


    <div class="card-body">


        <?php

        if (mysqli_num_rows($res) == 0) {

        ?>

            <p class="text-muted">
                No service providers found.
                Add your first service provider to get started.
            </p>

            <a
                href="add-service-provider.php"
                class="btn btn-primary"
            >
                + Add Provider
            </a>


        <?php

        }

        else {

        ?>


            <div class="table-responsive">

                <table class="table table-bordered table-striped">


                    <thead class="table-light">

                        <tr>

                            <th>ID</th>

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

                        while ($provider = mysqli_fetch_array($res)) {

                        ?>


                            <tr>


                                <td>

                                    <?php
                                    print $provider['provider_id'];
                                    ?>

                                </td>


                                <td>

                                    <strong>
                                        <?php
                                        print htmlspecialchars($provider['full_name']);
                                        ?>
                                    </strong>

                                    <br>

                                    <small>
                                        <?php
                                        print htmlspecialchars($provider['gender']);
                                        ?>
                                    </small>

                                </td>


                                <td>

                                    <?php
                                    print htmlspecialchars($provider['email']);
                                    ?>

                                    <br>

                                    <?php
                                    print htmlspecialchars($provider['phone']);
                                    ?>

                                </td>


                                <td>

                                    <?php

                                    if ($provider['category_name']) {

                                        print htmlspecialchars(
                                            $provider['category_name']
                                        );

                                    }
                                    else {

                                        print "No category";

                                    }

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    if ($provider['experience'] != "") {

                                        print $provider['experience'] . " years";

                                    }
                                    else {

                                        print "Not specified";

                                    }

                                    ?>

                                </td>


                                <td>

                                    <strong>
                                        <?php
                                        print htmlspecialchars($provider['area']);
                                        ?>
                                    </strong>

                                    <br>

                                    <?php
                                    print htmlspecialchars($provider['city']);
                                    ?>

                                </td>


                                <td>

                                    <?php

                                    if ($provider['availability'] == "Available") {

                                    ?>

                                        <span class="badge bg-success">
                                            Available
                                        </span>

                                    <?php

                                    }

                                    elseif ($provider['availability'] == "Busy") {

                                    ?>

                                        <span class="badge bg-warning text-dark">
                                            Busy
                                        </span>

                                    <?php

                                    }

                                    else {

                                    ?>

                                        <span class="badge bg-secondary">
                                            Offline
                                        </span>

                                    <?php

                                    }

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    if ($provider['account_status'] == "Active") {

                                    ?>

                                        <span class="badge bg-primary">
                                            Active
                                        </span>

                                    <?php

                                    }

                                    elseif ($provider['account_status'] == "Blocked") {

                                    ?>

                                        <span class="badge bg-danger">
                                            Blocked
                                        </span>

                                    <?php

                                    }

                                    else {

                                    ?>

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    <?php

                                    }

                                    ?>

                                </td>


                                <td>

                                    <?php
                                    print htmlspecialchars(
                                        $provider['created_at']
                                    );
                                    ?>

                                </td>


                                <td>

                                    <a
                                        href="edit-service-provider.php?id=<?php print $provider['provider_id']; ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>


                                    <a
                                        href="delete-service-provider.php?id=<?php print $provider['provider_id']; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure?');"
                                    >
                                        Delete
                                    </a>

                                </td>


                            </tr>


                        <?php

                        }

                        ?>


                    </tbody>

                </table>

            </div>


        <?php

        }

        ?>


    </div>

</div>


<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>
```
