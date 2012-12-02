<?php

namespace CtrlAuth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \CtrlAuth\Service\UserService */
        $service = $this->getDomainService('CtrlAuthUser');
        $user = $service->getAuthenticatedUser();

        return new ViewModel(array(
            'user' => $user
        ));
    }
}
