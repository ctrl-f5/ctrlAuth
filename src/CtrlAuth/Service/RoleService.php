<?php

namespace CtrlAuth\Service;

use \CtrlAuth\Domain;
use CtrlAuth\Domain\User;
use CtrlAuth\Domain\Role;

class RoleService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'CtrlAuth\Domain\Role';

    public function getByName($name)
    {
        $entities = $this->getEntityManager()
            ->createQuery('SELECT e FROM '.$this->entity.' e WHERE e.name = :name')
            ->setParameter('name', $name)
            ->getResult();
        if (!count($entities)) {
            //TODO: fix exception
            throw new \Exception($this->entity.' not found with name: '.$name);
        }
        return $entities[0];
    }

    public function getAssignableToUser(User $user)
    {
        $entities = $this->getEntityManager()
            ->createQuery('SELECT e FROM '.$this->entity.' e LEFT JOIN e.users u WHERE u.id IS NULL OR u.id != :id')
            ->setParameter('id', $user->getId())
            ->getResult();
        return $entities;
    }

    public function getByUser(User $user)
    {
        $entities = $this->getEntityManager()
            ->createQuery('SELECT e FROM '.$this->entity.' e JOIN e.users u WHERE u.id = :id')
            ->setParameter('id', $user->getId())
            ->getResult();
        return $entities;
    }

    public function getGuestRoles()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        try {
            return array(
                $roleService->getById(1),
            );
        } catch (\Exception $e) {
            return array();
        }
    }
}
