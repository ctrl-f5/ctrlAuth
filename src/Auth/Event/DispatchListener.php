<?php

namespace Ctrl\Module\Auth\Event;

use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerInterface;

class DispatchListener implements
    \Zend\EventManager\ListenerAggregateInterface,
    \Zend\ServiceManager\ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager = array();

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), $priority);
    }

    /**
     * Detach listeners from an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function preDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $routeParams = $e->getRouteMatch()->getParams();

        // build resource names
        $moduleResource = implode('.', array(
            \Ctrl\Permissions\Resources::SET_ROUTES,
            $routeParams['__NAMESPACE__'],
        ));
        $controllerResource = implode('.', array(
            \Ctrl\Permissions\Resources::SET_ROUTES,
            $routeParams['controller'],
        ));
        $actionResource = implode('.', array(
            $controllerResource,
            $routeParams['action'],
        ));
        var_dump($routeParams, $moduleResource, $controllerResource, $actionResource);
        $serviceManager = $e->getApplication()->getServiceManager();
        /** @var $authService \Ctrl\Module\Auth\Service\UserService */
        $authService = $serviceManager->get('DomainServiceLoader')->get('CtrlAuthUser');
        /** @var $acl \Ctrl\Permissions\Acl */
        $acl = $serviceManager->get('CtrlAuthAcl');
        $user = $authService->getAuthenticatedUser();
        var_dump($user->isGuestUser());
        $resource = $actionResource;
        if (!$user->hasAccessTo($resource)) {
            $resource = $controllerResource;
            if (!$acl->hasResource($resource)) {
                $resource = $moduleResource;
                if (!$acl->hasResource($resource)) {
                    $resource = false;
                }
            }
        }
        if (!$resource || !$user->hasAccessTo($resource)) {
            $e->setError('access denied');
            /** @var $viewManager \Zend\Mvc\View\Http\ViewManager */
            $viewManager = $serviceManager->get('ViewManager');
            $view = new \Zend\View\Model\ViewModel(array(
                'user' => $user,
                'resource' => $actionResource,
            ));
            $view->setTemplate('error/access-denied');
            $e->getViewModel()->addChild($view);
            return false;
        }
    }

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    public function getServiceManager(ServiceManager $serviceManager)
    {
        return $this->serviceManager;
    }
}
