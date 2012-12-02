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

    public function testCanBuildUrlToDefaultRoute()
    {
        $module = new \CtrlAuth\Module();
        $serviceManager = $this->getServiceManager($module->getRouterConfig());
        /** @var $router \Zend\Mvc\Router\SimpleRouteStack */
        $factory = new \Zend\Mvc\Service\RouterFactory();
        $router = $factory->createService($serviceManager);
        $url = $router->assemble(array(), array(
            'name' => 'ctrl_auth'
        ));
        $this->assertEquals('/auth', $url);
    }

    public function testCanBuildUrlToRouteWithController()
    {
        $module = new \CtrlAuth\Module();
        $serviceManager = $this->getServiceManager($module->getRouterConfig());
        /** @var $router \Zend\Mvc\Router\SimpleRouteStack */
        $factory = new \Zend\Mvc\Service\RouterFactory();
        $router = $factory->createService($serviceManager);
        $url = $router->assemble(array(
            'controller' => 'role',
            'action' => 'index',
        ), array(
            'name' => 'ctrl_auth'
        ));
        $this->assertEquals('/auth/role', $url);
    }

    public function testCanBuildUrlToRouteWithControllerAndAction()
    {
        $module = new \CtrlAuth\Module();
        $serviceManager = $this->getServiceManager($module->getRouterConfig());
        /** @var $router \Zend\Mvc\Router\SimpleRouteStack */
        $factory = new \Zend\Mvc\Service\RouterFactory();
        $router = $factory->createService($serviceManager);
        $url = $router->assemble(array(
            'controller' => 'role',
            'action' => 'test',
        ), array(
            'name' => 'ctrl_auth'
        ));
        $this->assertEquals('/auth/role/test', $url);
    }

    public function testCanBuildUrlToRouteWithControllerAndActionAndId()
    {
        $module = new \CtrlAuth\Module();
        $serviceManager = $this->getServiceManager($module->getRouterConfig());
        /** @var $router \Zend\Mvc\Router\SimpleRouteStack */
        $factory = new \Zend\Mvc\Service\RouterFactory();
        $router = $factory->createService($serviceManager);
        $url = $router->assemble(array(
            'controller' => 'role',
            'action' => 'test',
            'id' => 1,
        ), array(
            'name' => 'ctrl_auth/id'
        ));
        $this->assertEquals('/auth/role/test/1', $url);
    }
}
