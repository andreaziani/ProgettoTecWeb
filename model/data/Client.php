<?php
namespace Model\Data;
use Model\Data\RegisteredUser;
    class Client extends RegisteredUser {
        private $credit;
        public function __construct($userData, $registeredData) {
            parent::__construct($userData, $registeredData);
            $this->credit = $registeredData["Credit"];
        }
    }
?>