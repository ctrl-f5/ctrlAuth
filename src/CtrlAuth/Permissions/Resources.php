<?php

namespace CtrlAuth\Permissions;

class Resources extends \Ctrl\Permissions\Resources
{
    /**
     * Sets
     */
    const SET_ACTIONS   = 'actions.CtrlAuth';

    /**
     * Actions
     */
    const ACTION_USER_REMOVE = 'User.remove';
    const ACTION_ROLE_EDIT_SYSTEM = 'Role.editSystemRole';

    const RESOURCE_ROUTE_AUTH = 'routes.CtrlAuth';
    const RESOURCE_ROUTE_LOGIN = 'routes.CtrlAuth.Login.index';

    public function getSets()
    {
        return array_merge(
            parent::getSets(),
            array(
                self::SET_ACTIONS,
            )
        );
    }

    public function getResources($set = null)
    {
        $resources = array_merge(
            parent::getResources(),
            array(
                self::SET_ROUTES => array(
                    'CtrlAuth\Controller' => array(
                        'Index',
                        'Login',
                        'User',
                    ),
                ),
                self::SET_ACTIONS => array(
                    self::ACTION_USER_REMOVE,
                ),
                self::SET_MENU => array(
                    'CtrlAuth' => array(
                        'Login' => array(
                            'index'
                        ),
                        'User' => array(
                            'index'
                        ),
                        'Role' => array(
                            'index',
                            'edit'
                        ),
                        'Permission' => array(
                            'index'
                        ),
                    )
                ),
            )
        );

        $resources = $this->assertResources($resources);

        return $resources;
    }
}
