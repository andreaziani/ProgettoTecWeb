<?php
namespace View;

class View
{
    private $listTemplate = array(
        "index" => "/ProgettoTecWeb/view/index.php",
        "providerProductsList" => "/ProgettoTecWeb/view/template/providerproductslist/providerProductsList.php",
        "loginPage" => "ProgettoTecWeb/view/template/access/loginPage.php",
    );
    static $instance = null;

    private function __construct()
    {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new View();
        }
        return self::$instance;
    }

    public function redirect($name){
        header("location: " . $this->listTemplate[$name]);
    }
}