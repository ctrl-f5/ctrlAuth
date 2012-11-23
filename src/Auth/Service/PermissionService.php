<?php

namespace Ctrl\Module\Auth\Service;

use \Ctrl\Module\Auth\Domain;;
use Ctrl\Form\Form;

class PermissionService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'Ctrl\Module\Auth\Domain\Permission';

    /**
     * @param Domain\Role $role
     * @param $resource Domain\Resource|string
     */
    public function allowRoleToResource(Domain\Role $role, $resource)
    {


    }
}
