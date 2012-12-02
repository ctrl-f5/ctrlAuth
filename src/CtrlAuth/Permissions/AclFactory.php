<?php

namespace CtrlAuth\Permissions;

use Ctrl\Permissions\AclFactory as BaseFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclFactory extends BaseFactory
{
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $acl = parent::createService($serviceManager);

        /*
         * fetch roles and their permissions
         */
        $roleService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthRole');
        $acl->addRoles($roleService->getAll());
        /*
        * Always allow login page
        */
        $acl->allow($acl->getRoles(), \CtrlAuth\Permissions\Resources::RESOURCE_ROUTE_AUTH);
        $acl->allow($acl->getRoles(), \CtrlAuth\Permissions\Resources::RESOURCE_ROUTE_LOGIN);

        return $acl;
    }
}
