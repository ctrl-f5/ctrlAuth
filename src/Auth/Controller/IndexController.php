<?php

namespace Ctrl\Module\Auth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \Ctrl\Module\Auth\Service\UserService */
        $service = $this->getDomainService('CtrlAuthUser');
        $user = $service->getAuthenticatedUser();

        return new ViewModel(array(
            'user' => $user
        ));
    }
}
