<?php

namespace CtrlAuth\Permissions;

class Resources extends \Ctrl\Permissions\Resources
{
    /**
     * Sets
     */
    const SET_ACTIONS = 'CtrlAuth.actions';

    /**
     * Actions
     */
    const ACTION_USER_REMOVE = 'User.remove';

    const RESOURCE_ROUTE_AUTH = 'routes.CtrlAuth\Controller';
    const RESOURCE_ROUTE_LOGIN = 'routes.CtrlAuth\Controller.Login';

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
                        'Login',
                    ),
                ),
                self::SET_ACTIONS => array(
                    self::ACTION_USER_REMOVE,
                ),
            )
        );

        $resources = $this->assertResources($resources);

        return $resources;
    }
}
