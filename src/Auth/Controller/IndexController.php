<?php

namespace Ctrl\Module\Auth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        /** @var $service \Ctrl\Module\Auth\Service\UserService */
        $service = $this->getDomainService('AuthUser');
        var_dump($service->getAuthenticatedUser());
    }
}
