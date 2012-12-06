<?php

namespace CtrlAuth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;
use CtrlAuth\Domain\Role;

class PermissionController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \CtrlAuth\Service\PermissionService */
        //$service = $this->getDomainService('CtrlAuthPermission');
        //$permissions = $service->getAll();

        /** @var $auth \Ctrl\Permissions\Acl */
        $auth = $this->getServiceLocator()->get('CtrlAuthAcl');
        $roleService = $this->getDomainService('CtrlAuthRole');
        $role = $roleService->getById($this->params()->fromRoute('id'));
        //$userService = $this->getDomainService('CtrlAuthUser');

        return new ViewModel(array(
            'auth' => $auth,
            'role' => $role,
        ));
    }

    public function editAction()
    {
        $userService = $this->getDomainService('CtrlAuthUser');
        $user = $userService->getById($this->params()->fromRoute('id'));

        $form = new \CtrlAuth\Form\User\Edit();

        return new ViewModel(array(
            'user' => $user,
            'form' => $form,
        ));
    }

    public function allowRoleAction()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        /** @var $role Role */
        $role = $roleService->getById($this->params()->fromRoute('role'));
        $premissionService = $this->getDomainService('CtrlAuthPermission');
        $role->allowResource($this->params()->fromRoute('resource'));
        $roleService->persist($role);

        return $this->redirect()->toRoute('ctrl_auth/id', array(
            'controller' => 'permission',
            'action' => 'index',
            'id' => $role->getId(),
        ));
    }

    public function denyRoleAction()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        /** @var $role Role */
        $role = $roleService->getById($this->params()->fromRoute('role'));
        $premissionService = $this->getDomainService('CtrlAuthPermission');
        $role->denyResource($this->params()->fromRoute('resource'));
        $roleService->persist($role);

        return $this->redirect()->toRoute('ctrl_auth/id', array(
            'controller' => 'permission',
            'action' => 'index',
            'id' => $role->getId(),
        ));
    }
}
