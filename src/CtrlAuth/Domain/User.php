<?php

namespace CtrlAuth\Domain;

use Doctrine\ORM\Mapping as ORM;

class User extends \Ctrl\Domain\PersistableServiceLocatorAwareModel
{
    /**
     * @var bool
     */
    protected $systemUser;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var Role[]
     */
    protected $roles;

    /**
     * @var \Zend\Permissions\Acl\Role\GenericRole
     */
    protected $combinedRole;

    public function __construct()
    {
        $this->roles = new Collection();
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
        if (!$this->getServiceLocator()) return false;

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
        $roles = new Collection($roles);
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function linkRole(Role $role)
    {
        $this->roles[] = $role;
        $role->getUsers()->add($this);
        return $this;
    }

    public function unlinkRole(Role $role)
    {
        $this->roles->removeElement($role);
        $role->getUsers()->removeElement($this);
        return $this->roles;
    }

    public function isGuestUser()
    {
        return false;
    }

    /**
     * @param $systemUser
     * @return User
     */
    public function setIsSystemUser($systemUser)
    {
        $this->systemUser = (bool)$systemUser;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSystemUser()
    {
        return $this->systemUser;
    }
}