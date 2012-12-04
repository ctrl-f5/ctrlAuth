<?php

namespace CtrlAuth\Domain;

use Doctrine\ORM\Mapping as ORM;

class Resource extends \Ctrl\Domain\PersistableModel
    implements \Zend\Permissions\Acl\Resource\ResourceInterface
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
            throw new \CtrlAuth\Exception('invalid Resource name');
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
     * @param \CtrlAuth\Domain\Permission $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return \CtrlAuth\Domain\Permission
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Defined by ResourceInterface; returns the Resource identifier
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->getName();
    }

    /**
     * Defined by ResourceInterface; returns the Resource identifier
     * Proxies to getResourceId()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getResourceId();
    }
}