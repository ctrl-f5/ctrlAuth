<?php

namespace CtrlAuth;

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
        $authPredispatch    = new \CtrlAuth\Event\DispatchListener();
        $authPredispatch->setServiceManager($serviceManager);
        $eventManager->attachAggregate($authPredispatch, 100);
    }

    public function getConfig()
    {
        return array_merge(
            $this->getRouterConfig(),
            include __DIR__ . '/../../config/module.config.php'
        );

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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CtrlAuthNavigation' => 'CtrlAuth\Navigation\AuthNavigationFactory',
                'CtrlAuthAcl' => 'CtrlAuth\Permissions\AclFactory',
            ),
            'aliases' => array(
                'Acl' => 'CtrlAuthAcl'
            )
        );
    }

    public function getRouterConfig()
    {
        $config = array('router' => array(
            'routes' => array(
                'ctrl_auth' => \Ctrl\Mvc\Router\Http\DefaultSegment::factory(
                    'CtrlAuth\Controller',
                    '/auth',
                    array(
                        'role_edit' => array(
                            'type'    => 'Segment',
                            'may_terminate' => true,
                            'options' => array(
                                'route'    => '/role/edit/[:role][/:slug]',
                                'constraints' => array(
                                    'role'   => '[0-9]+',
                                    'controller'   => 'Role',
                                    'action'   => 'edit-tabs',
                                ),
                                'defaults' => array(
                                    'controller' => 'Role',
                                    'action' => 'edit-tabs',
                                    'slug' => 'default',
                                )
                            ),
                            'child_routes' => array(
                                'query' => array(
                                    'type'    => 'Query',
                                    'may_terminate' => true,
                                ),
                            ),

                        ),
                        'role_perm' => array(
                            'type'    => 'Segment',
                            'may_terminate' => true,
                            'options' => array(
                                'route'    => '/role/permission/[:task]/[:role]/[:resource]',
                                'constraints' => array(
                                    'role'   => '[0-9]+',
                                ),
                                'defaults' => array(
                                    'controller'   => 'permission',
                                    'action' => 'change-permission',
                                ),
                            ),
                        ),
                    )
                ),
            ),
        ));
        return $config;
    }
}
