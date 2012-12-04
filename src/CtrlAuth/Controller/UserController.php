<?php

namespace CtrlAuth\Controller;

use CtrlAuth\Domain;
use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class UserController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \CtrlAuth\Service\UserService */
        $service = $this->getDomainService('CtrlAuthUser');
        $users = $service->getAll();

        $service->getNewEntity();
        return new ViewModel(array(
            'users' => $users
        ));
    }

    public function editAction()
    {
        $userService = $this->getDomainService('CtrlAuthUser');
        /** @var $user User */
        if ($this->params()->fromRoute('id')) {
            $user = $userService->getById($this->params()->fromRoute('id'));
            $form = new \CtrlAuth\Form\User\Edit();
            $form->loadModel($user);
        } else {
            $user = new Domain\User();
            $form = new \CtrlAuth\Form\User\Add();
        }


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


    public function addAction()
    {
        $userService = $this->getDomainService('CtrlAuthUser');
        /** @var $user User */
        $user = new Domain\User();
        $form = new \CtrlAuth\Form\User\Add();


        $form->setAttribute('action', $this->url()->fromRoute('ctrl_auth', array(
            'controller' => 'user',
            'action' => 'add'
        )));
        $form->setReturnUrl($this->url()->fromRoute('ctrl_auth', array(
            'controller' => 'user',
        )));

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $elems = $form->getElements();
                $user->setUsername($elems[$form::ELEM_USERNAME]->getValue());
                $user->setPassword($elems[$form::ELEM_PASSWORD]->getValue());
                $user->setEmail($elems[$form::ELEM_EMAIL]->getValue());
                $userService->persist($user);
                if ($this->params()->fromPost('save-add-roles')) {
                    return $this->redirect()->toUrl($this->url()->fromRoute('ctrl_auth/id', array(
                        'controller' => 'user',
                        'action' => 'add-role',
                        'id' => $user->getId(),
                    )));
                }
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
        /** @var $userService \CtrlAuth\Service\UserService */
        $userService = $this->getDomainService('CtrlAuthUser');
        /** @var $user \CtrlAuth\Domain\User */
        $user = $userService->getById($this->params()->fromRoute('id'));
        /** @var $roleService \CtrlAuth\Service\RoleService */
        $roleService = $this->getDomainService('CtrlAuthRole');

        if ($this->params()->fromQuery('role')) {
            /** @var $role \CtrlAuth\Domain\Role */
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
        /** @var $userService \CtrlAuth\Service\UserService */
        $userService = $this->getDomainService('CtrlAuthUser');
        /** @var $user \CtrlAuth\Domain\User */
        $user = $userService->getById($this->params()->fromRoute('id'));
        /** @var $roleService \CtrlAuth\Service\RoleService */
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
