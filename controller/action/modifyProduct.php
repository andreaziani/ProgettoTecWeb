<?php
namespace Controller\Action;

use Controller\Controller;

    session_start();
    $controller = Controller::getInstance();
    $view = View::getInstance();
    if(isset($_GET["name"]) && isset($_GET["description"]) && isset($_GET["price"])){
        $provider = $_SESSION["username"];
        $name = $_GET["name"];
        $description = $_GET["description"];
        $price = $_GET["price"];

        $controller->modifyProduct($name, $description, $price, $provider);
    }  
?>
