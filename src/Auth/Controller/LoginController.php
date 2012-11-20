<?php

namespace Ctrl\Module\Auth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \Ctrl\Module\Auth\Service\UserService */
        $service = $this->getDomainService('CtrlAuthUser');
        $form = $service->getLoginForm();

        $form->setAttribute('action', $this->url()->fromRoute('ctrl_auth', array(
            'controller' => 'login',
            'action' => 'index',
        )));

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $elems = $form->getElements();
                $service->authenticate(
                    $elems[$form::ELEM_USERNAME]->getValue(),
                    $elems[$form::ELEM_PASSWORD]->getValue()
                );
                return $this->redirect()->toRoute('ctrl_auth');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function logoutAction()
    {
        /** @var $service \Ctrl\Module\Auth\Service\UserService */
        $service = $this->getDomainService('CtrlAuthUser');
        $service->resetAuthentication();

        return $this->redirect()->toRoute('ctrl_auth');
    }
}
