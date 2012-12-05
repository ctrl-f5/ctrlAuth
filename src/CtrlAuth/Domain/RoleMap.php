<?php

namespace CtrlAuth\Domain;

use Doctrine\ORM\Mapping as ORM;

class RoleMap extends \Ctrl\Domain\PersistableModel
{
    /**
     * @var Resource
     */
    protected $parent;

    /**
     * @var int
     */
    protected $order;

    /**
     * @var Role
     */
    protected $role;


    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Resource $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Resource
     */
    public function getParent()
    {
        return $this->parent;
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