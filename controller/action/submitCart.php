<?php
namespace Controller\Action;

// require and include all the files
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/ProgettoTecWeb/vendor/autoload.php')){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/ProgettoTecWeb/vendor/autoload.php';
}

use Controller\Controller;

session_start();

if (isset($_SESSION["user"]) and isset ($_SESSION["cart"]))  {
    //Controller::getInstance()->insertProduct($provider, $name, $description, $price, $tmp_name, $filename, $category);
}

?>