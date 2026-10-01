<?php

$pageTitle = "Contacts";
$pageCss = "contacts.css";

$adminPath = "../../";
$assetPath = "../../../";

require_once "../../../config/db.php";


/* Get Contacts */

$q = "select * from contact
      order by contact_id desc";

$res = mysqli_query($conn, $q);

$totalContacts = mysqli_num_rows($res);


ob_start();

?>


<div class="contacts-page">


    <div class="contacts-heading">

        <h2>
            Contacts
        </h2>

        <p>
            View contact messages submitted by customers.
        </p>

    </div>


    <div class="contacts-card">


        <div class="contacts-card-header">

            <h3>
                All Contacts
            </h3>

            <span class="contacts-count">

                <?php echo $totalContacts; ?>

                Contacts

            </span>

        </div>


        <!-- Search -->

        <div class="admin-search">

            <input
                type="text"
                id="contactSearch"
                placeholder="Search contacts..."
            >

        </div>


        <?php

        if ($totalContacts > 0)
        {

        ?>


            <div class="contacts-table-wrapper">


                <table class="contacts-table">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Message
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    while ($contact = mysqli_fetch_array($res))
                    {

                    ?>


                        <tr class="contact-search-row">


                            <td>

                                <?php

                                echo $contact["contact_id"];

                                ?>

                            </td>


                            <td>

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $contact["full_name"]
                                    );

                                    ?>

                                </strong>

                            </td>


                            <td>

                                <a
                                    href="mailto:<?php echo htmlspecialchars($contact["email"]); ?>"
                                    class="contact-email"
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $contact["email"]
                                    );

                                    ?>

                                </a>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $contact["phone"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $contact["subject"]
                                );

                                ?>

                            </td>


                            <td>

                                <div class="contact-message">

                                    <?php

                                    echo htmlspecialchars(
                                        $contact["message"]
                                    );

                                    ?>

                                </div>

                            </td>


                            <td>

                                <span class="contact-status">

                                    <?php

                                    echo htmlspecialchars(
                                        $contact["message_status"]
                                    );

                                    ?>

                                </span>

                            </td>


                            <td>

                                <?php

                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $contact["created_at"]
                                    )
                                );

                                ?>

                            </td>


                        </tr>


                    <?php

                    }

                    ?>


                    </tbody>


                </table>


                <div
                    id="contactNoResult"
                    class="admin-no-result"
                >
                    No contacts found.
                </div>


            </div>


        <?php

        }
        else
        {

        ?>


            <div class="contacts-empty">

                <h3>
                    No Contacts Found
                </h3>

                <p>
                    There are no contact messages available.
                </p>

            </div>


        <?php

        }

        ?>


    </div>


</div>


<script>

var searchInput = document.getElementById("contactSearch");

var rows = document.querySelectorAll(".contact-search-row");

var noResult = document.getElementById("contactNoResult");


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