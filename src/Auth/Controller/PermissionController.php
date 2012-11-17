<?php

namespace Ctrl\Module\Auth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class PermissionController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \Ctrl\Module\Auth\Service\PermissionService */
        //$service = $this->getDomainService('CtrlAuthPermission');
        //$permissions = $service->getAll();

        /** @var $auth \Ctrl\Permissions\Acl */
        $auth = $this->getServiceLocator()->get('CtrlAuthAcl');
        $roleService = $this->getDomainService('CtrlAuthRole');
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

        $form = new \Ctrl\Module\Auth\Form\User\Edit();

        return new ViewModel(array(
            'user' => $user,
            'form' => $form,
        ));
    }
}
