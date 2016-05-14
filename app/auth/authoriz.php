<?php

class Authoriz extends Nette\Security\Permission {
    
    /** @var Nette\Database\Context */
    private $conn;
    
    function __construct(Nette\Database\Context $database)
    {
        $this->conn = $database;
        
        $this->addRole('guest');
        $this->addRole('user', 'guest');
        $this->addRole('admin', 'user');
        
        $this->addResource('contact');
        $this->addResource('cabinet');
        
        $this->allow('admin', 'contact', 'view');
        //$this->deny('guest', 'contact', 'view');
    }
    
    function isAllowed($role, $resource, $privilege) {
        Tracy\Debugger::log($role . $resource . $privilege);
        Tracy\Debugger::log($this);
        
        return parent::isAllowed($role, $resource, $privilege);
        //return TRUE; // returns either TRUE or FALSE
    }

}
