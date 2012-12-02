<?php

namespace CtrlAuth\Domain;

use Doctrine\ORM\Mapping as ORM;

class Permission extends \Ctrl\Domain\PersistableModel
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @var bool
     */
    protected $isAllowed;

    /**
     * @var Role
     */
    protected $role;

    public function __construct(Role $role, Resource $resource, $allowed = false)
    {
        $this->setRole($role);
        $this->setResource($resource);
    }

    /**
     * @param boolean $isAllowed
     */
    public function setAllowed($isAllowed)
    {
        $this->isAllowed = $isAllowed;
    }

    /**
     * @return boolean
     */
    public function isAllowed()
    {
        return $this->isAllowed;
    }

    /**
     * @param Resource $resource
     * @return Permission
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return \CtrlAuth\Domain\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param \CtrlAuth\Domain\Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return \CtrlAuth\Domain\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}