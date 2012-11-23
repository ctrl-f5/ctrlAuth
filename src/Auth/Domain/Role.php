<?php

namespace Ctrl\Module\Auth\Domain;

use \Ctrl\Module\Auth\Domain;;
use Doctrine\ORM\Mapping as ORM;

class Role extends \Ctrl\Domain\PersistableServiceLocatorAwareModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var User
     */
    protected $users;

    /**
     * @var Permission[]|Collection
     */
    protected $permissions;

    public function __construct()
    {
        $this->permissions = new Collection();
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

    public function allowResource($resource)
    {
        $resource = $this->assertResource($resource);
        $permission = $this->getPermissionForResource($resource);
        $permission->setAllowed(true);
    }

    public function denyResource($resource)
    {
        $resource = $this->assertResource($resource);
        $permission = $this->getPermissionForResource($resource);
        $permission->setAllowed(false);
    }

    public function getPermissionForResource($resource)
    {
        $resource = $this->assertResource($resource);

        //find it
        foreach ($this->permissions as $p) {
            if ($p->getResource()->getName() == $resource->getName()) {
                return $p;
            }
        }
        //or create it
        $permission = new Permission($this, $resource);
        $this->permissions[] = $permission;
        return $permission;
    }

    /**
     * @param $resource
     * @return Resource
     */
    protected function assertResource($resource)
    {
        $resourceService = $this->getDomainService('CtrlAuthResource');
        if (is_string($resource)) {
            $res = $resourceService->getByName($resource);
            if (!$res) {
                $res = new Domain\Resource($resource);
                $resourceService->persist($res);
            }
            $resource = $res;
        }
        if (!($resource instanceof Resource)) {
            throw new \Ctrl\Module\Auth\Exception('invalid resource passed');
        }
        return $resource;
    }

    public function __toString()
    {
        return $this->getName();
    }
}