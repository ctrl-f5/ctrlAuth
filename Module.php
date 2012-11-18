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
        $eventManager        = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);

        $this->initAcl($serviceManager);
    }

    public function preDispatch(MvcEvent $e)
    {
        $routeParams = $e->getRouteMatch()->getParams();

        //build resource name
        $resource = implode('.', array(
            \Ctrl\Permissions\Resources::SET_ROUTES,
            $routeParams['controller'],
            $routeParams['action'],
        ));

        $serviceManager = $e->getApplication()->getServiceManager();
        $authService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthUser');
        $user = $authService->getAuthenticatedUser();
        if (!$user->hasAccessTo($resource)) {
            $e->setError('access denied');
            /** @var $viewManager \Zend\Mvc\View\Http\ViewManager */
            $viewManager = $serviceManager->get('ViewManager');
            $view = new \Zend\View\Model\ViewModel(array(
                'user' => $user,
                'resource' => $resource,
            ));
            $view->setTemplate('error/access-denied');
            $e->getViewModel()->addChild($view);
            return false;
        }
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
        /** @var $role \Ctrl\Module\Auth\Domain\Role */
        foreach ($roleService->getAll() as $role) {
            if (!$auth->hasRole($role->getName())) {
                $authRole = new \Zend\Permissions\Acl\Role\GenericRole($role->getName());
                $auth->addRole($authRole);
            } else {
                $authRole = $auth->getRole($role->getName());
            }
            foreach ($role->getPermissions() as $permission) {
                if ($permission->isAllowed() && $auth->hasResource($permission->getResource()->getName())) {
                    $auth->allow($authRole, $auth->getResource($permission->getResource()->getName()));
                }
            }
            /*
             * Always allow login page
             */
            $auth->allow($role->getName(), \Ctrl\Module\Auth\Permissions\Resources::RESOURCE_ROUTE_LOGIN);
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
