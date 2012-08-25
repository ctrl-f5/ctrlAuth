<?php

namespace Ctrl\Module\Auth\Controller;

use Ctrl\Controller\AbstractController;;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $user = new \Ctrl\Module\Auth\Domain\User();
        $user->setUsername('test');
        $user->setPassword('tester');
    }
}
