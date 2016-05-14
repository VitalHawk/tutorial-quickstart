<?php

use Nette\Security as NS;

class Authent extends Nette\Object implements NS\IAuthenticator {
    private $database;

    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->database->table('users')
            ->where('login', $username)
            ->where("password=md5(?)", $password)
            ->fetch();

        if (!$row) {
            throw new NS\AuthenticationException('User not found or invalid password.');
        }

//        if (!NS\Passwords::verify($password, $row->password)) {
//            throw new NS\AuthenticationException('Invalid password.');
//        }

        //return new NS\Identity($row->id, $row->role, array('username' => $row->username));
        return new NS\Identity($row->id,
                $row->isAdmin ? 'admin' :  'user', 
                array(
                    'login' => $row->login,
                    'name' => $row->name,
                    'surname' => $row->surname
                ));
    }
}
