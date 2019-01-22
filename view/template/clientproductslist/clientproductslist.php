<?php
// require and include all the files
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/ProgettoTecWeb/vendor/autoload.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/ProgettoTecWeb/vendor/autoload.php';
}

use Model\QueryManager;
use Utils\PathManager;

$db = new QueryManager();
$base = new PathManager();
$base->requireFromWebSitePath('header/_header.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/ProgettoTecWeb/view/template/clientproductslist/style.css">
<div class="container">
    <div class="input-group" id="research">
        <label class='hidden' for="searchBar">Search bar</label>
        <input class="form-control" id="searchBar" type="text" placeholder="Search product.." name="search" autocomplete="on">
        <div class="input-group-btn">
            <button class="btn btn-default" id="searchButton"><span class="glyphicon glyphicon-search"></span></button>
        </div>
    </div>
</div>

<div class='container'>
    <ul class="pagination">
    <?php 
            $query = 'SELECT DISTINCT(Categories.Name) FROM Categories, Products WHERE Products.ProviderId = "' . $_POST["username"] . '" AND Products.CategoryId = Categories.Id';
            $result = $db->queryDataToList($db->executeQuery($query));
            foreach($result as $row) {
                echo "<li><a class='category'>". $row["Name"]."</a></li>";
            }
    ?>
    </ul>
    <?php
        echo "<input id='provider' class='hidden' type='text' value='". $_POST["username"] . "' />";
    ?>
</div>
<div>
    <div>
        <ul id="productslist">
                <?php
                    $query = "SELECT * FROM Products WHERE ProviderId='". $_POST["username"] ."' AND IsActive=true ORDER BY Name";
                    $result = $db->queryDataToList($db->executeQuery($query));
                    foreach($result as $row) {
                            echo "<li class='product'>
                                    <input class='hidden id' name='id' type='number' value='" . $row["Id"] . "'/> 
                                    <h4 class='name'>" . $row["Name"] . "</h4>
                                    <img class='productimg' src=/ProgettoTecWeb/" . $row["Image"] . " alt='". $row["Description"] . "' />
                                    <p class='description'>" . $row["Description"] . "</p>
                                    <p class='price'> Price: € <span class='value'>" . $row["Price"] . "</span> </p>
                                    <div class='input-group buttons'>
                                        <label class='hidden' for='id". $row["Id"] . "'> Quantity </label>
                                        <input class='form-control quantity' type='number' min='0' name='quantity' value='0' id='id" . $row["Id"] . "'/>
                                        <div class='input-group-btn'>
                                            <button class='btn btn-default addToCart'>Add to cart</button>
                                        </div>
                                    </div>
                                </li>";
                    }
                ?>
        </ul>
    </div>
</div>
<script src="/ProgettoTecWeb/view/template/clientproductslist/productlist.js"></script>
<?php
    $base->requireFromWebSitePath('footer/_footer.php');
?>