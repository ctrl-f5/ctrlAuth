<?php

namespace Ctrl\Module\Auth\Service;

use \Ctrl\Module\Auth\Domain;
use Ctrl\Module\Auth\Domain\User;
use Ctrl\Module\Auth\Domain\Role;

class ResourceService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'Ctrl\Module\Auth\Domain\Resource';

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
