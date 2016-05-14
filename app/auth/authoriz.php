<?php

class Authoriz extends Nette\Security\Permission {
    
    /** @var Nette\Database\Context */
    private $conn;
    
    function __construct(Nette\Database\Context $database)
    {
        $this->conn = $database;
    }
    
    function isAllowed($role, $resource, $privilege) {
        //return $this->isAllowed($role, $resource, $privilege);
        return TRUE; // returns either TRUE or FALSE
    }

}
