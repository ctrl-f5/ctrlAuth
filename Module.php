<?php

namespace Ctrl\Module\Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap($e)
    {
        $application = $e->getApplication();
        /** @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $application->getServiceManager();
        /** @var $eventManager \Zend\EventManager\EventManager */
        $eventManager        = $e->getApplication()->getEventManager();
        $authPredispatch = new \Ctrl\Module\Auth\Event\DispatchListener();
        $authPredispatch->setServiceManager($serviceManager);
        $eventManager->attachAggregate($authPredispatch, 100);

        $this->initAcl($serviceManager);
    }

    public function initAcl(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $config = $serviceManager->get('Configuration');
        /** @var $auth \Ctrl\Permissions\Acl */
        $auth = $serviceManager->get('CtrlAuthAcl');

        /*
         * Set all system resources
         */
        if (isset($config['acl']) && isset($config['acl']['resources'])) {
            foreach ($config['acl']['resources'] as $class) {
                /** @var $inst \Ctrl\Permissions\Resources */
                $inst = new $class;
                if ($inst instanceof \Ctrl\Permissions\Resources) {
                    $auth->addSystemResources($inst);
                }
            }
        }

        /*
         * fetch roles and their permissions
         */
        $roleService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthRole');
        $auth->addRoles($roleService->getAll());
        /*
        * Always allow login page
        */
        $auth->allow($auth->getRoles(), \Ctrl\Module\Auth\Permissions\Resources::RESOURCE_ROUTE_LOGIN);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/Auth/',
                ),
            ),
        );
    }
}
