<?php

namespace Ctrl\Module\Auth\Permissions;

class Resources extends \Ctrl\Permissions\Resources
{
    const SET_ACTIONS = 'Ctrl.Module.Auth.actions';

    const ACTION_USER_REMOVE = 'User.remove';

    const RESOURCE_ROUTE_LOGIN = 'routes.Ctrl\Module\Auth\Controller\Login.index';

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
                    'Ctrl\Module\Auth\Controller\Index' => array(
                        'index',
                        'auth',
                    ),
                    'Ctrl\Module\Auth\Controller\Login' => array(
                        'index',
                    ),
                    'Ctrl\Module\Auth\Controller\User' => array(
                        'index',
                        'edit',
                    )
                ),
                self::SET_ACTIONS => array(
                    self::ACTION_USER_REMOVE,
                ),
            )
        );

        if ($set) {
            if (isset($set, $resources)) {
                return $this->flattenResourceArray($resources[$set], $set);
            }
            return array();
        }
        return $set;
    }
}
