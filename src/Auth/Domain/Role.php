<?php

namespace Ctrl\Module\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

class Role
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var User
     */
    protected $users;

    /**
     * @var Permission[]
     */
    protected $permissions;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Ctrl\Module\Auth\Domain\Permission[] $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return \Ctrl\Module\Auth\Domain\Permission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param \Ctrl\Module\Auth\Domain\User $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return \Ctrl\Module\Auth\Domain\User
     */
    public function getUsers()
    {
        return $this->users;
    }
}