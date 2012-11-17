<?php

namespace Ctrl\Module\Auth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class UserController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \Ctrl\Module\Auth\Service\UserService */
        $service = $this->getDomainService('CtrlAuthUser');
        $users = $service->getAll();

        return new ViewModel(array(
            'users' => $users
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
