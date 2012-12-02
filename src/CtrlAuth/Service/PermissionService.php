<?php

namespace CtrlAuth\Service;

use \CtrlAuth\Domain;;
use Ctrl\Form\Form;

class PermissionService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'CtrlAuth\Domain\Permission';

    /**
     * @param Domain\Role $role
     * @param $resource Domain\Resource|string
     */
    public function allowRoleToResource(Domain\Role $role, $resource)
    {


    }
}
