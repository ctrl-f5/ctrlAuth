<?php

namespace Ctrl\Module\Auth;

return array(
    'router' => array(
        'routes' => array(
            'ctrl_auth' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/auth[/:controller][/:action]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ctrl\Module\Auth\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'id' => array(
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route'    => '/[:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                        ),
                        'child_routes' => array(
                            'query' => array(
                                'type'    => 'Query',
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                    'query' => array(
                        'type'    => 'Query',
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Ctrl\Module\Auth\Controller\Index' => 'Ctrl\Module\Auth\Controller\IndexController',
            'Ctrl\Module\Auth\Controller\Login' => 'Ctrl\Module\Auth\Controller\LoginController',
            'Ctrl\Module\Auth\Controller\User' => 'Ctrl\Module\Auth\Controller\UserController',
            'Ctrl\Module\Auth\Controller\Permission' => 'Ctrl\Module\Auth\Controller\PermissionController',
        ),
    ),
    'domain_services' => array(
        'invokables' => array(
            'AuthUser' => 'Ctrl\Module\Auth\Service\UserService',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            'ctrl/auth' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Auth' => 'Ctrl\Permissions\Acl'
        )
    ),
    'acl' => array(
        'resources' => array(
            'AuthResources' => 'Ctrl\Module\Auth\Permissions\Resources'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'auth_driver' => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => array(__DIR__.'/../src/Auth/Domain', __DIR__.'/entities')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Ctrl\Module\Auth\Domain' => 'auth_driver'
                )
            )
        ),
    )
);
