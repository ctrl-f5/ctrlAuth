<?php

namespace CtrlAuth;

return array(
    'controllers' => array(
        'invokables' => array(
            'CtrlAuth\Controller\Index' => 'CtrlAuth\Controller\IndexController',
            'CtrlAuth\Controller\Login' => 'CtrlAuth\Controller\LoginController',
            'CtrlAuth\Controller\User' => 'CtrlAuth\Controller\UserController',
            'CtrlAuth\Controller\Role' => 'CtrlAuth\Controller\RoleController',
            'CtrlAuth\Controller\Permission' => 'CtrlAuth\Controller\PermissionController',
        ),
    ),
    'domain_services' => array(
        'invokables' => array(
            'CtrlAuthUser' => 'CtrlAuth\Service\UserService',
            'CtrlAuthRole' => 'CtrlAuth\Service\RoleService',
            'CtrlAuthPermission' => 'CtrlAuth\Service\PermissionService',
            'CtrlAuthResource' => 'CtrlAuth\Service\ResourceService',
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
    'acl' => array(
        'resources' => array(
            'AuthResources' => 'CtrlAuth\Permissions\Resources'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'auth_driver' => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => array(__DIR__.'/../src/CtrlAuth/Domain', __DIR__.'/entities')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CtrlAuth\Domain' => 'auth_driver'
                )
            )
        ),
    ),
    'navigation' => array(
        'ctrl_auth' => array(
            array(
                'label' => 'account',
                'route' => 'ctrl_auth/default',
                'type' => 'Ctrl\Navigation\Page\Mvc',
                'resource' => 'routes.CtrlAuth\Controller',
            ),
            array(
                'label' => 'users',
                'route' => 'ctrl_auth/default',
                'type' => 'Ctrl\Navigation\Page\Mvc',
                'resource' => 'routes.CtrlAuth\Controller\User',
                'params' => array(
                    'controller' => 'user'
                ),
                'pages' => array(
                    array(
                        'label' => 'login',
                        'route' => 'ctrl_auth/default',
                        'type' => 'Ctrl\Navigation\Page\Mvc',
                        'resource' => 'routes.CtrlAuth\Controller\Login',
                        'params' => array(
                            'controller' => 'login',
                        ),
                    ),
                ),
            ),
            array(
                'label' => 'roles',
                'route' => 'ctrl_auth/default',
                'type' => 'Ctrl\Navigation\Page\Mvc',
                'resource' => 'routes.CtrlAuth\Controller\Role',
                'params' => array(
                    'controller' => 'role'
                ),
                'pages' => array(
                    array(
                        'label' => 'permissions',
                        'route' => 'ctrl_auth/default/id',
                        'type' => 'Ctrl\Navigation\Page\Mvc',
                        'resource' => 'routes.CtrlAuth\Controller\Permission',
                        'params' => array(
                            'controller' => 'permission'
                        ),
                    ),
                )
            ),
        )
    )
);
