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
        /** @var $role Role */
        $role = $roleService->getById($this->params()->fromRoute('id'));

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

    public function changePermissionAction()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        /** @var $role Role */
        $role = $roleService->getById($this->params()->fromRoute('role'));
        $resource = $this->params()->fromRoute('resource');

        switch ($this->params()->fromRoute('task')) {
            case 'allow':
                $role->allowResource($resource);
                break;
            case 'deny':
                $role->denyResource($resource);
                break;
            case 'inherit':
                $role->inheritResource($resource);
                break;
            default:
                throw new \CtrlAuth\Exception('invalid action provided');
                break;
        }

        $roleService->persist($role);

        return $this->redirect()->toRoute('ctrl_auth/default/id', array(
            'controller' => 'permission',
            'action' => 'index',
            'id' => $role->getId(),
        ));
    }
}
