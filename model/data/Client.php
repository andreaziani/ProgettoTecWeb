<?php
namespace Model\Data;
use Model\Data\RegisteredUser;
    class Client extends RegisteredUser {
        public $credit;
        public $currentCartId;
        public function __construct($userData, $registeredData) {
            parent::__construct($userData, $registeredData);
            $this->credit = $registeredData["Credit"];
            $this->currentCartId = $registeredData["CurrentCartId"];
        }
    }
?>