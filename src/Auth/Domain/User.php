<?php

namespace Ctrl\Module\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

class User extends \Ctrl\Domain\PersistableServiceLocatorAwareModel
{
    protected $id;

    protected $username;

    protected $password;

    protected $email;

    /**
     * @var Role[]
     */
    protected $roles;

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


    public function hasAccessTo($resource)
    {
        /** @var $auth \Ctrl\Permissions\Acl */
        $auth = $this->getServiceLocator()->get('CtrlAuthAcl');
        if ($auth->hasResource($resource)) {
            foreach ($this->getRoles() as $role) {
                if ($auth->isAllowed($role->getName(), $auth->getResource($resource))) {
                    return true;
                }
            }
        }
        return false;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}