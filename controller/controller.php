<?php
namespace Controller;

use Model\ProductManager;
use Model\UserManager;
use Model\CartManager;
use Model\NotificationManager;
use Model\OrderProvider;
use View\View;

class Controller
{
    static $instance = null;
    private $productManager;
    private $userManager;
    private $cartManager;
    private $notificationManager;
    private $view;
    public function __construct()
    {
        $this->productManager = new ProductManager();
        $this->userManager = new UserManager();
        $this->cartManager = new CartManager();
        $this->notificationManager = new NotificationManager();
        $this->view = View::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Controller();
        }
        return self::$instance;
    }

    public function insertProduct($provider, $name, $description, $price, $tmp_name, $filename, $category)
    {
        $this->productManager->insertProduct($provider, $name, $description, $price, $tmp_name, $filename, $category);
        $this->view->redirect("providerProductsList");
    }

    public function removeProduct($id)
    {
        $this->productManager->removeProduct($id);
        $this->view->redirect("providerProductsList");
    }

    public function modifyProduct($name, $description, $price, $id)
    {
        $this->productManager->modifyProduct($name, $description, $price, $id);
        $this->view->redirect("providerProductsList");
    }

    public function login($username, $password) {
        if ($this->userManager->verifyLogin($username, $password)) {
            $this->startSession();
            $_SESSION["user"] = $this->userManager->getUser($username);
            if ($_SESSION["user"]->currentCartId != null) {
                $_SESSION["cart"] = $this->cartManager->getCart($_SESSION["user"]->currentCartId);
            }
            $this->view->redirect("mainPage");
        } else {
            $this->startSession();
            $_SESSION["error"] = "Username or password not correct";
            $this->view->redirect("loginPage");
        }
    }

    public function register($userData) {
        if ($this->userManager->canRegister($userData) and $this->userManager->register($userData)) {
            $this->startSession();
            $_SESSION["user"] = $this->userManager->getUser($username);
            $this->view->redirect("mainPage");
        } else {
            $_SESSION["error"] = "Registration failed";
            $this->view->redirect("registerPage");
        }
        
    }

    public function searchProducts($key){
        return $this->productManager->searchProducts($key);
    }

    public function searchProvider($key){
        return $this->productManager->searchProvider($key);
    }

    public function logout(){
        $this->startSession();
        session_destroy();
        $this->view->redirect("mainPage");
    }
    
    public function updateProfileInformations($data, $tableName, $username){
        if($this->userManager->updateProfileInformations($data, $tableName, $username) > 0){
            $_SESSION["user"] = $this->userManager->getUser($username);
            switch($tableName){
                case "Clients":
                    $this->view->redirect("clientProfile");
                    break;
                case "Providers":
                    $this->view->redirect("providerProfile");
                    break;
            }
        }
    }

    public function checkoutOrder($nominative, $spot, $dateTime) {
        if ($this->cartManager->checkout($_SESSION["cart"], $nominative, $spot, $dateTime)) {
            foreach ($this->cartManager->getOrders($_SESSION["cart"]) as $order) {
                $this->cartManager->startOrder($order);
                $this->notificationManager->createNewOrderNotification($this->cartManager->getOrderData($order));
            }
            echo $this->userManager->removeCart($_SESSION["user"]);
            unset($_SESSION["cart"]);
        }
        $this->view->redirect("mainPage");
    }

    public function addProductToCart($data){
        self::startSession();
        if(!isset($_SESSION["cart"]) || $_SESSION["cart"] == ''){
            $_SESSION["cart"] = $this->cartManager->createCart($_SESSION["user"]);
        }
        return $this->cartManager->addProductToCart($_SESSION["cart"], new \Model\Data\CartEntry($data));
    }

    public function getNotifications() {
        $_SESSION["notifications"] = $this->notificationManager->getUnreadNotifications($_SESSION["user"]);
        return $_SESSION["notifications"];
    }

    public function tryReview($orderId) {
        if ($this->userManager->canReview($_SESSION["user"], $orderId)) {
            $_SESSION["order"] = $orderId;
            echo $this->view->getHref("reviewPage");
        }
    }
    
    public function trySend($orderId) {
        if ($this->userManager->canSend($_SESSION["user"], $orderId)) {
            $_SESSION["order"] = $orderId;
            echo $this->view->getHref("sendOrderPage");
        }
    }
    
    public function setRead() {
        $this->notificationManager->setAllRead($_SESSION["user"]);
        echo $this->view->getHref("mainPage");
    }

    public function submitReview($description, $rank) {
        if ($this->userManager->canReview($_SESSION["user"], $_SESSION["order"])) {
            $this->userManager->submitReview($_SESSION["order"], $description, $rank);
            $this->notificationManager->setRead($_SESSION["order"]);
            unset($_SESSION["order"]);
        }
        $this->view->redirect("mainPage");
    }

    public function insertCategory($name){
        $this->productManager->insertCategory($name);
        $this->view->redirect("categories");
    }
    
    public function sendOrder($order, $minutes) {
        $orderData = $this->cartManager->getOrderData($order);
        $this->notificationManager->createOrderComingNotification($orderData, $minutes);
        $this->createReviewNotification->createOrderArrivedNotification($orderData, $minutes);
        $this->cartManager->setOrderArrived($order);
    }

    /**
     * Method to redirect the page when the action inputs are wrong.
     */
    public function actionError() {
        $this->view->redirect("index");
    }
    /**
     * Utility method for check if a session is already started.
     */
    private function startSession(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $_SESSION["orderProvider"] = new OrderProvider();
        }
    }
}
?>