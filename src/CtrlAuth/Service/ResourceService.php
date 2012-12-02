<?php

namespace CtrlAuth\Service;

use \CtrlAuth\Domain;
use CtrlAuth\Domain\User;
use CtrlAuth\Domain\Role;

class ResourceService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'CtrlAuth\Domain\Resource';

    public function getByName($name)
    {
        try {
            return $this->getEntityManager()
                ->createQuery('SELECT e FROM '.$this->entity.' e WHERE e.name = :name')
                ->setParameter('name', $name)
                ->getSingleResult();
        } catch (\Exception $e) {
            return false;
        }
    }
}
