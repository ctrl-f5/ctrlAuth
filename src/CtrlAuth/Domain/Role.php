<?php

namespace CtrlAuth\Domain;

use \CtrlAuth\Domain;;
use Doctrine\ORM\Mapping as ORM;

class Role extends \Ctrl\Domain\PersistableServiceLocatorAwareModel
    implements \Zend\Permissions\Acl\Role\RoleInterface
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
     * @var Role[]
     */
    protected $children;

    /**
     * @var Role[]
     */
    protected $parents;

    /**
     * @var bool
     */
    protected $systemRole;

    /**
     * @var Permission[]|Collection
     */
    protected $permissions;

    public function __construct()
    {
        $this->users = new Collection();
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
     * @param bool $systemUser
     */
    public function setIsSystemRole($systemUser)
    {
        $this->systemRole = (bool)$systemUser;
    }

    /**
     * @return bool
     */
    public function isSystemRole()
    {
        return $this->systemRole;
    }

    /**
     * @param \CtrlAuth\Domain\Permission[] $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return \CtrlAuth\Domain\Permission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param \CtrlAuth\Domain\User $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return \CtrlAuth\Domain\User[]|Collection
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
     * @throws \CtrlAuth\Exception
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
            throw new \CtrlAuth\Exception('invalid resource passed');
        }
        return $resource;
    }

    /**
     * Defined by RoleInterface; returns the Role identifier
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->getName();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setParents($parents)
    {
        $this->parents = $parents;
    }

    public function getParents()
    {
        return $this->parents;
    }

}