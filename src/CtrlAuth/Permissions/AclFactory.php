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

        // add roles
        /** @var $roleService RoleService */
        $roleService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthRole');
        /** @var $roles Role[] */
        $roles = $roleService->getAll();
        $this->addRoleMap($acl, $roles);


        // set permissions
        $this->assertPermissions($acl, $roles);

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

    /**
     * @param \Ctrl\Permissions\Acl $acl
     * @param array|Role[] $roles
     */
    protected function assertPermissions(Acl $acl, $roles)
    {
        foreach ($roles as $r) {
            foreach ($r->getPermissions() as $p) {
                if ($p->isAllowed()) {
                    $acl->allow($r, $p->getResource()->getResourceId());
                } else {
                    $acl->deny($r, $p->getResource()->getResourceId());
                }
            }
        }
    }
}
