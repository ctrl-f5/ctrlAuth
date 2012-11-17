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
        $auth = $this->getServiceLocator()->get('Auth');

        return new ViewModel(array(
            'auth' => $auth
        ));
    }

    public function editAction()
    {
        $userService = $this->getDomainService('AuthUser');
        $user = $userService->getById($this->params()->fromRoute('id'));

        $form = new \Ctrl\Module\Auth\Form\User\Edit();

        return new ViewModel(array(
            'user' => $user,
            'form' => $form,
        ));
    }
}
