<?php

namespace Ctrl\Module\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

class Resource extends \Ctrl\Domain\PersistableModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Permission
     */
    protected $permissions;

    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (!is_string($name) || !strlen(trim($name))) {
            throw new \Ctrl\Module\Auth\Exception('invalid Resource name');
        }

        $this->name = trim($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Ctrl\Module\Auth\Domain\Permission $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return \Ctrl\Module\Auth\Domain\Permission
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}