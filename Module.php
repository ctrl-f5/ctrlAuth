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

        $this->initAcl($serviceManager);
    }

    public function initAcl(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $config = $serviceManager->get('Configuration');
        /** @var $auth \Ctrl\Permissions\Acl */
        $auth = $serviceManager->get('Auth');
        if (isset($config['acl']) && isset($config['acl']['resources'])) {
            foreach ($config['acl']['resources'] as $class) {
                /** @var $inst \Ctrl\Permissions\Resources */
                $inst = new $class;
                if ($inst instanceof \Ctrl\Permissions\Resources) {
                    $auth->addResources($inst);
                }
            }
        }
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
