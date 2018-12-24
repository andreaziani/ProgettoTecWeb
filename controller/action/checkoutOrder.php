<?php
namespace Controller\Action;

// require and include all the files
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/ProgettoTecWeb/vendor/autoload.php')){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/ProgettoTecWeb/vendor/autoload.php';
}

use Controller\Controller;

session_start();

if (isset($_SESSION["user"]) and isset ($_SESSION["cart"])
    and isset($_POST["deliverySpot"]) and isset($_POST["deliveryDate"])
    and isset($_POST["deliveryTime"])) {
        $spot = InputValidator::validate($_POST["deliverySpot"]);
        $d = InputValidator::validate($_POST["deliveryDate"]);
        $t = InputValidator::validate($_POST["deliveryTime"]);
        $dateTime = date($d . "T" . $t);
        Controller::getInstance()->checkoutOrder($spot, $dateTime);
}

?>