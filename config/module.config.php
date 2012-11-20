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
                    'permission' => array(
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route'    => '/[:id][/:role]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                                'role'   => '[0-9]+',
                            ),
                        ),
                    ),
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
            'Ctrl\Module\Auth\Controller\Role' => 'Ctrl\Module\Auth\Controller\RoleController',
            'Ctrl\Module\Auth\Controller\Permission' => 'Ctrl\Module\Auth\Controller\PermissionController',
        ),
    ),
    'domain_services' => array(
        'invokables' => array(
            'CtrlAuthUser' => 'Ctrl\Module\Auth\Service\UserService',
            'CtrlAuthRole' => 'Ctrl\Module\Auth\Service\RoleService',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            'ctrl/auth' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'error/access-denied'   => __DIR__ . '/../view/error/access-denied.phtml',
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'CtrlAuthAcl' => 'Ctrl\Permissions\Acl',
        ),
        'factories' => array(
            'CtrlAuthNavigation' => 'Ctrl\Module\Auth\Navigation\AuthNavigationFactory',
        ),
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
    ),
    'navigation' => array(
        'ctrl_auth' => array(
            array(
                'label' => 'users',
                'route' => 'ctrl_auth',
                'type' => 'Ctrl\Navigation\Page\Mvc',
                'resource' => 'routes.Ctrl\Module\Auth\Controller\User',
                'params' => array(
                    'controller' => 'user'
                ),
            ),
            array(
                'label' => 'roles',
                'route' => 'ctrl_auth',
                'type' => 'Ctrl\Navigation\Page\Mvc',
                'resource' => 'routes.Ctrl\Module\Auth\Controller\Role',
                'params' => array(
                    'controller' => 'role'
                ),
                'pages' => array(
                    array(
                        'label' => 'permissions',
                        'route' => 'ctrl_auth/id',
                        'type' => 'Ctrl\Navigation\Page\Mvc',
                        'resource' => 'routes.Ctrl\Module\Auth\Controller\Permission',
                        'params' => array(
                            'controller' => 'permission'
                        ),
                    ),
                )
            ),
        )
    )
);
