<?php

namespace CtrlAuth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class RoleController extends AbstractController
{
    public function indexAction()
    {
        $userService = $this->getDomainService('CtrlAuthUser');
        try {
            $user = $userService->getById($this->params()->fromRoute('id'));
        } catch (\Exception $e) {
            $user = null;
        }

        /** @var $service \CtrlAuth\Service\RoleService */
        $service = $this->getDomainService('CtrlAuthRole');
        if ($user) {
            $roles = $service->getByUser($user);
        } else {
            $roles = $service->getAll();
        }

        return new ViewModel(array(
            'roles' => $roles,
            'user' => $user,
        ));
    }

    public function editAction()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        $role = $roleService->getById($this->params()->fromRoute('id'));

        $form = new \CtrlAuth\Form\Role\Edit();
        $form->loadModel($role);

        $form->setAttribute('action', $this->url()->fromRoute('ctrl_auth/id', array(
            'controller' => 'role',
            'action' => 'edit',
            'id' => $role->getId()
        )));
        $form->setReturnUrl($this->url()->fromRoute('ctrl_auth', array(
            'controller' => 'role',
        )));

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $elems = $form->getElements();
                $role->setName($elems[$form::ELEM_NAME]->getValue());
                $roleService->persist($role);
                return $this->redirect()->toUrl($form->getReturnurl());
            }
        }

        return new ViewModel(array(
            'role' => $role,
            'form' => $form,
        ));
    }
}
