<?php

namespace Ctrl\Module\Auth\Service;

use \Ctrl\Module\Auth\Domain;
use Ctrl\Module\Auth\Domain\User;
use Ctrl\Module\Auth\Domain\Role;

class RoleService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'Ctrl\Module\Auth\Domain\Role';

    public function getGuestRoles()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        return array(
            $roleService->getById(2),
        );
    }
}
