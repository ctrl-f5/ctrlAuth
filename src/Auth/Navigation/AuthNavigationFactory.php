<?php

namespace Ctrl\Module\Auth\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class AuthNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'ctrl_auth';
    }
}
