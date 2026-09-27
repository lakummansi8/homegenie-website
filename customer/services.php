<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";


/* Get Categories */

$q = "select * from categories order by category_name";

$categoryResult = mysqli_query($conn, $q);


/* Get Services */

$q2 = "select * from services order by service_id desc";

$result = mysqli_query($conn, $q2);


/* Get Service Names for Filter */

$serviceNames = array();


$allServices = mysqli_query(
    $conn,
    "select * from services"
);


while ($oneService = mysqli_fetch_array($allServices))
{

    $providerId = $oneService["provider_id"];


    /* Get Provider Category */

    $q3 = "select category_id
           from service_providers
           where provider_id = $providerId
           and account_status = 'Active'";

    $providerResult = mysqli_query($conn, $q3);


    if (mysqli_num_rows($providerResult) > 0)
    {

        $provider = mysqli_fetch_array($providerResult);

        $categoryId = $provider["category_id"];


        $serviceName = $oneService["service_name"];


        /*
         * Store service name with category
         */

        if (!isset($serviceNames[$serviceName]))
        {
            $serviceNames[$serviceName] = array();
        }


        if (!in_array($categoryId, $serviceNames[$serviceName]))
        {
            $serviceNames[$serviceName][] = $categoryId;
        }

    }

}


$pageTitle = "Services";

$pageCss = "services.css";


ob_start();

?>


<div class="customer-services">


    <!-- Page Heading -->

    <div class="services-heading">

        <h1>
            Available Services
        </h1>

        <p>
            Find the service you need from our service providers.
        </p>

    </div>


    <!-- Search and Filter -->

    <div class="service-search-box">


        <!-- Search -->

        <div class="search-group">

            <label>
                Search Service
            </label>

            <input
                type="text"
                id="serviceSearch"
                placeholder="Search service or provider"
            >

        </div>


        <!-- Category -->

        <div class="filter-group">

            <label>
                Category
            </label>

            <select id="categoryFilter">

                <option value="">
                    All Categories
                </option>


                <?php

                while ($category = mysqli_fetch_array($categoryResult))
                {

                ?>

                    <option
                        value="<?php echo $category["category_id"]; ?>"
                    >

                        <?php
                        echo htmlspecialchars(
                            $category["category_name"]
                        );
                        ?>

                    </option>

                <?php

                }

                ?>

            </select>

        </div>


        <!-- Service -->

        <div class="filter-group">

            <label>
                Service
            </label>

            <select id="serviceFilter">

                <option value="">
                    All Services
                </option>


                <?php

                foreach ($serviceNames as $name => $categories)
                {

                ?>

                    <option
                        value="<?php echo htmlspecialchars(strtolower($name)); ?>"
                        data-categories="<?php echo implode(",", $categories); ?>"
                    >

                        <?php
                        echo htmlspecialchars($name);
                        ?>

                    </option>

                <?php

                }

                ?>

            </select>

        </div>


        <!-- Price -->

        <div class="filter-group">

            <label>
                Price
            </label>

            <select id="priceFilter">

                <option value="">
                    All Prices
                </option>

                <option value="500">
                    Under ₹500
                </option>

                <option value="1000">
                    ₹500 - ₹1000
                </option>

                <option value="1001">
                    Above ₹1000
                </option>

            </select>

        </div>


    </div>


    <!-- Service List -->

    <div
        class="service-list"
        id="serviceList"
    >


        <?php

        $serviceFound = false;


        if (mysqli_num_rows($result) > 0)
        {

            while ($service = mysqli_fetch_array($result))
            {

                $providerId = $service["provider_id"];

                $providerName = "";
                $area = "";
                $city = "";
                $categoryId = "";
                $categoryName = "";


                /* Get Provider */

                $q4 = "select * from service_providers
                       where provider_id = $providerId
                       and account_status = 'Active'";

                $providerResult = mysqli_query(
                    $conn,
                    $q4
                );


                if (mysqli_num_rows($providerResult) > 0)
                {

                    $provider =
                        mysqli_fetch_array(
                            $providerResult
                        );


                    $providerName =
                        $provider["full_name"];


                    $area =
                        $provider["area"];


                    $city =
                        $provider["city"];


                    $categoryId =
                        $provider["category_id"];


                    /* Get Category */

                    if ($categoryId != "")
                    {

                        $q5 = "select category_name
                               from categories
                               where category_id = $categoryId";

                        $categoryData =
                            mysqli_query(
                                $conn,
                                $q5
                            );


                        if (mysqli_num_rows($categoryData) > 0)
                        {

                            $category =
                                mysqli_fetch_array(
                                    $categoryData
                                );


                            $categoryName =
                                $category["category_name"];

                        }

                    }


                    $serviceFound = true;

        ?>


                    <div
                        class="service-item"

                        data-search="<?php

                        echo htmlspecialchars(
                            strtolower(
                                $service["service_name"] . " " .
                                $providerName . " " .
                                $area . " " .
                                $city . " " .
                                $categoryName
                            )
                        );

                        ?>"

                        data-category="<?php

                        echo $categoryId;

                        ?>"

                        data-service="<?php

                        echo htmlspecialchars(
                            strtolower(
                                $service["service_name"]
                            )
                        );

                        ?>"

                        data-price="<?php

                        echo $service["price"];

                        ?>"
                    >


                        <!-- Service Details -->

                        <div class="service-details">


                            <div class="provider-name">

                                <span>
                                    Provider / Firm
                                </span>

                                <h2>
                                    <?php

                                    echo htmlspecialchars(
                                        $providerName
                                    );

                                    ?>
                                </h2>

                            </div>


                            <div class="service-name">

                                <span>
                                    Service Provided
                                </span>

                                <strong>
                                    <?php

                                    echo htmlspecialchars(
                                        $service["service_name"]
                                    );

                                    ?>
                                </strong>

                            </div>


                            <div class="category-name">

                                <span>
                                    Category
                                </span>

                                <strong>
                                    <?php

                                    echo htmlspecialchars(
                                        $categoryName
                                    );

                                    ?>
                                </strong>

                            </div>


                            <div class="service-location">

                                <span>
                                    Location
                                </span>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $area
                                    );
                                    ?>

                                    ,

                                    <?php
                                    echo htmlspecialchars(
                                        $city
                                    );
                                    ?>

                                </strong>

                            </div>


                        </div>


                        <!-- Price and Buttons -->

                        <div class="service-price">


                            <span>
                                Starting Price
                            </span>


                            <strong>

                                ₹<?php

                                echo number_format(
                                    $service["price"],
                                    2
                                );

                                ?>

                            </strong>


                            <div class="service-buttons">


                                <a
                                    href="provider-details.php?provider_id=<?php echo $providerId; ?>&service_id=<?php echo $service["service_id"]; ?>"
                                    class="view-button"
                                >
                                    View Details
                                </a>


                                <a
                                    href="book-service.php?service_id=<?php echo $service["service_id"]; ?>"
                                    class="book-button"
                                >
                                    Book Service
                                </a>


                            </div>


                        </div>


                    </div>


        <?php

                }

            }

        }


        if (!$serviceFound)
        {

        ?>


            <div class="empty-state">

                <h2>
                    No Services Available
                </h2>

                <p>
                    There are currently no services available.
                </p>

            </div>


        <?php

        }


        ?>


        <div
            class="no-search-result"
            id="noSearchResult"
        >

            <h2>
                No Services Found
            </h2>

            <p>
                Try changing your search or filters.
            </p>

        </div>


    </div>


</div>


<script>


var searchInput =
    document.getElementById("serviceSearch");


var categoryFilter =
    document.getElementById("categoryFilter");


var serviceFilter =
    document.getElementById("serviceFilter");


var priceFilter =
    document.getElementById("priceFilter");


var serviceItems =
    document.querySelectorAll(".service-item");


var serviceOptions =
    serviceFilter.querySelectorAll(
        "option[data-categories]"
    );


var noSearchResult =
    document.getElementById("noSearchResult");


/*
 * Change Service Options
 * according to Category
 */

function updateServiceFilter()
{

    var selectedCategory =
        categoryFilter.value;


    var currentService =
        serviceFilter.value;


    serviceOptions.forEach(
        function(option)
        {

            var categories =
                option.getAttribute(
                    "data-categories"
                );


            var categoryList =
                categories.split(",");


            if (
                selectedCategory == "" ||
                categoryList.includes(
                    selectedCategory
                )
            )
            {

                option.style.display = "block";

            }

            else
            {

                option.style.display = "none";

            }

        }
    );


    /*
     * Check if selected service
     * belongs to selected category
     */

    if (currentService != "")
    {

        var selectedOption =
            serviceFilter.querySelector(
                'option[value="' +
                currentService +
                '"]'
            );


        if (selectedOption)
        {

            var categories =
                selectedOption.getAttribute(
                    "data-categories"
                );


            var categoryList =
                categories.split(",");


            if (
                selectedCategory != "" &&
                !categoryList.includes(
                    selectedCategory
                )
            )
            {

                serviceFilter.value = "";

            }

        }

    }


    filterServices();

}


/*
 * Filter Services
 */

function filterServices()
{

    var searchText =
        searchInput.value.toLowerCase();


    var selectedCategory =
        categoryFilter.value;


    var selectedService =
        serviceFilter.value.toLowerCase();


    var selectedPrice =
        priceFilter.value;


    var found = false;


    serviceItems.forEach(
        function(service)
        {

            var searchData =
                service.getAttribute(
                    "data-search"
                );


            var category =
                service.getAttribute(
                    "data-category"
                );


            var serviceName =
                service.getAttribute(
                    "data-service"
                );


            var servicePrice =
                parseFloat(
                    service.getAttribute(
                        "data-price"
                    )
                );


            /*
             * Search
             */

            var searchMatch =
                searchData.includes(
                    searchText
                );


            /*
             * Category
             */

            var categoryMatch =
                selectedCategory == "" ||
                category == selectedCategory;


            /*
             * Service
             */

            var serviceMatch =
                selectedService == "" ||
                serviceName == selectedService;


            /*
             * Price
             */

            var priceMatch = true;


            if (selectedPrice == "500")
            {

                priceMatch =
                    servicePrice < 500;

            }


            else if (selectedPrice == "1000")
            {

                priceMatch =
                    servicePrice >= 500 &&
                    servicePrice <= 1000;

            }


            else if (selectedPrice == "1001")
            {

                priceMatch =
                    servicePrice > 1000;

            }


            /*
             * Show / Hide
             */

            if (
                searchMatch &&
                categoryMatch &&
                serviceMatch &&
                priceMatch
            )
            {

                service.style.display =
                    "flex";

                found = true;

            }

            else
            {

                service.style.display =
                    "none";

            }

        }
    );


    if (found)
    {

        noSearchResult.style.display =
            "none";

    }

    else
    {

        noSearchResult.style.display =
            "block";

    }

}


/*
 * Search
 */

searchInput.addEventListener(
    "input",
    filterServices
);


/*
 * Category
 */

categoryFilter.addEventListener(
    "change",
    updateServiceFilter
);


/*
 * Service
 */

serviceFilter.addEventListener(
    "change",
    filterServices
);


/*
 * Price
 */

priceFilter.addEventListener(
    "change",
    filterServices
);


</script>


<?php

$pageContent = ob_get_clean();

require_once "layout/customer-layout.php";

?>