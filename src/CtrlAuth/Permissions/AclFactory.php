<?php

namespace CtrlAuth\Permissions;

use Ctrl\Permissions\AclFactory as BaseFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Ctrl\Permissions\Acl;
use CtrlAuth\Domain\Role;
use CtrlAuth\Service\RoleService;

class AclFactory extends BaseFactory
{
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        /** @var $acl Acl */
        $acl = parent::createService($serviceManager);

        /** @var $roleService RoleService */
        $roleService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthRole');
        /** @var $roles Role[] */
        $roles = $roleService->getAll();
        $roles = $this->addRoleMap($acl, $roles);

        /*
        * Always allow login page
        */
        //$acl->allow($acl->getRoles(), \CtrlAuth\Permissions\Resources::RESOURCE_ROUTE_AUTH);
        //$acl->allow($acl->getRoles(), \CtrlAuth\Permissions\Resources::RESOURCE_ROUTE_LOGIN);

        return $acl;
    }

    /**
     * @param \Ctrl\Permissions\Acl $acl
     * @param array|Role[] $roles
     */
    protected function addRoleMap(Acl $acl, $roles = array())
    {
        foreach ($roles as $role) {
            if ($acl->hasRole($role->getRoleId())) {
                $acl->removeRole($role->getRoleId());
            }
            $parents = $role->getParents(true);
            foreach ($parents as $p) {
                if (!$acl->hasRole($p)) {
                    $acl->addRole($p);
                }
            }
            $acl->addRole($role, $parents);
        }
    }
}
