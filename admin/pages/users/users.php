<?php

$pageTitle = "Customers";
$assetPath = "../../../";
$adminPath = "../../";

require_once "../../../config/db.php";

$sql = "SELECT * FROM users ORDER BY user_id DESC";
$result = mysqli_query($conn, $sql);

ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h3>Customers</h3>
        <p class="text-muted">View and manage HomeGenie customer accounts.</p>
    </div>

    <div class="col-md-4 text-md-end">
        <a href="add-user.php" class="btn btn-primary">+ Add Customer</a>
    </div>
</div>

<div class="card">

    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

        <strong>All Customers</strong>

        <span class="badge bg-light text-dark">
            <?php echo mysqli_num_rows($result); ?> Customers
        </span>

    </div>

    <div class="card-body">

        <?php if(mysqli_num_rows($result) == 0) { ?>

            <p class="text-muted">
                There are currently no customer accounts in the system.
            </p>

            <a href="add-user.php" class="btn btn-primary">
                Add First Customer
            </a>

        <?php } else { ?>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                     <?php $srno = 1;?>
                        <?php while($user = mysqli_fetch_array($result)) { ?>

                        

                            <tr>

                                <td><?php print $srno; ?></td>

                                <td>
                                    <strong>
                                        <?php echo $user['full_name']; ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php echo $user['email']; ?>
                                    <br>
                                    <?php echo $user['phone']; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php echo $user['city']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $user['address']; ?>
                                </td>

                                <td>
                                    <?php
                                    echo date("d M Y", strtotime($user['created_at']));
                                    ?>
                                </td>

                                <td>

                                    <?php if($user['account_status'] == "Active") { ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php } elseif($user['account_status'] == "Blocked") { ?>

                                        <span class="badge bg-danger">
                                            Blocked
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-secondary">
                                            <?php echo $user['account_status']; ?>
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>

                                    <a href="edit-user.php?id=<?php echo $user['user_id']; ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>

                                    <a href="delete-user.php?id=<?php echo $user['user_id']; ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure?');">
                                        Delete
                                    </a>

                                </td>

                            </tr>
                              <?php $srno++; ?>

                        <?php } ?>

                    </tbody>

                </table>

            </div>
           

        <?php } ?>

    </div>

</div>

<?php

$pageContent = ob_get_clean();

require_once "../../layout/admin-layout.php";

?>