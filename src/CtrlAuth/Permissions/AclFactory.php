<?php

namespace CtrlAuth\Permissions;

use Ctrl\Permissions\AclFactory as BaseFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Acl\Acl;
use CtrlAuth\Domain\Role;

class AclFactory extends BaseFactory
{
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        /** @var $acl Acl */
        $acl = parent::createService($serviceManager);

        $roleService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthRole');
        /** @var $roles Role[] */
        $roles = $this->addRoles($acl, $roleService->getAll());

        /*
        * Always allow login page
        */
        $acl->allow($acl->getRoles(), \CtrlAuth\Permissions\Resources::RESOURCE_ROUTE_AUTH);
        $acl->allow($acl->getRoles(), \CtrlAuth\Permissions\Resources::RESOURCE_ROUTE_LOGIN);

        return $acl;
    }

    /**
     * @param \Zend\Permissions\Acl\Acl $acl
     * @param array|Role[] $roles
     */
    protected function addRoles(Acl $acl, $roles = array())
    {
        foreach ($roles as $role) {
            if (!$acl->hasRole($role->getRoleId())) {
                $parent = array();
                if (count($role->getParents())) {
                    foreach ($role->getParents() as $p) {
                        $parent[] = $p->getRoleId();
                    }
                }
                $acl->addRole($role, $parent);

                if (count($role->getChildren())) {
                    $this->addRoles($acl, $role->getChildren());
                }
            }
        }
    }
}
