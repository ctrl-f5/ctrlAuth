<?php

namespace Ctrl\Module\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

class Permission
{
    /**
     * @var int
     */
    protected $id;

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
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return \Ctrl\Module\Auth\Domain\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param \Ctrl\Module\Auth\Domain\Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return \Ctrl\Module\Auth\Domain\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}