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
        $form->loadModel($user);

        $form->setAttribute('action', $this->url()->fromRoute('ctrl_auth/id', array(
            'controller' => 'user',
            'action' => 'edit',
            'id' => $user->getId()
        )));
        $form->setReturnUrl($this->url()->fromRoute('ctrl_auth', array(
            'controller' => 'user',
        )));

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $elems = $form->getElements();
                $user->setUsername($elems[$form::ELEM_USERNAME]->getValue());
                $userService->persist($user);
                return $this->redirect()->toUrl($form->getReturnurl());
            }
        }

        return new ViewModel(array(
            'user' => $user,
            'form' => $form,
        ));
    }

    public function addRoleAction()
    {
        /** @var $userService \Ctrl\Blog\Service\UserService */
        $userService = $this->getDomainService('CtrlAuthUser');
        /** @var $user \Ctrl\Module\Auth\Domain\User */
        $user = $userService->getById($this->params()->fromRoute('id'));
        /** @var $roleService \Ctrl\Module\Auth\Service\RoleService */
        $roleService = $this->getDomainService('CtrlAuthRole');

        if ($this->params()->fromQuery('role')) {
            /** @var $role \Ctrl\Module\Auth\Domain\Role */
            $role = $roleService->getById($this->params()->fromQuery('role'));
            $user->linkRole($role);
            $userService->persist($user);
            return $this->redirect()->toRoute('ctrl_auth/id', array(
                'controller' => 'role',
                'id' => $user->getId(),
            ));
        } else {
            $roles = $roleService->getAssignableToUser($user);

            return new ViewModel(array(
                'user' => $user,
                'roles' => $roles,
            ));
        }
    }

    public function removeRoleAction()
    {
        /** @var $userService \Ctrl\Blog\Service\UserService */
        $userService = $this->getDomainService('CtrlAuthUser');
        /** @var $user \Ctrl\Module\Auth\Domain\User */
        $user = $userService->getById($this->params()->fromRoute('id'));
        /** @var $roleService \Ctrl\Module\Auth\Service\RoleService */
        $roleService = $this->getDomainService('CtrlAuthRole');

        if ($this->params()->fromQuery('role')) {
            $role = $roleService->getById($this->params()->fromQuery('role'));
            $user->unlinkRole($role);
            $userService->persist($user);
        }
        return $this->redirect()->toRoute('ctrl_auth/id', array(
            'controller' => 'role',
            'id' => $user->getId(),
        ));
    }
}
