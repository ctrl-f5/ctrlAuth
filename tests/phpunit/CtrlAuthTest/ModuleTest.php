<?php

namespace CtrlAuthTest;

use CtrlTest\ApplicationTestCase;

class ModuleTest extends ApplicationTestCase
{
    public function testCanRetrieveDomainServiceLoader()
    {
        $loader = $this->getApplicationServiceManager()->get('DomainServiceLoader');
        $this->assertInstanceOf('\Zend\ServiceManager\ServiceManager', $loader);
    }

    public function testHasOverridenDefaultRedirectControllerPlugin()
    {
        $loader = $this->getApplicationServiceManager()->get('ControllerPluginManager');
        $redirectPlugin = $loader->get('Redirect');
        $this->assertInstanceOf('\Ctrl\Mvc\Controller\Plugin\Redirect', $redirectPlugin);
    }

    public function testHasOverridenDefaultNavigationViewHelper()
    {
        $loader = $this->getApplicationServiceManager()->get('ViewHelperManager');
        $navHelper = $loader->get('Navigation');
        $this->assertInstanceOf('\Ctrl\View\Helper\Navigation\Navigation', $navHelper);
    }

    public function testCanBuildUrlToRoute()
    {
        return;
        $module = new \Ctrl\Module\Auth\Module();
        $serviceManager = $this->getServiceManager($module->getRouterConfig());
        /** @var $router \Zend\Mvc\Router\SimpleRouteStack */
        $router = $serviceManager->get('router');
        $url = $router->assemble(array(), array(
            'name' => 'ctrl_auth'
        ));
        $this->assertEqual('/auth', $url);
    }
}
