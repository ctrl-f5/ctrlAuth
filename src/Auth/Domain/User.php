<?php

namespace Ctrl\Module\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

class User
{
    protected $id;

    protected $username;

    protected $password;

    protected $email;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPassword($password)
    {
        $this->password = sha1($password);
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function authenticate($password)
    {
        return sha1($password) == $this->password;
    }
}