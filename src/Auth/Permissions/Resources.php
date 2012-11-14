<?php

namespace Ctrl\Module\Auth\Permissions;

class Resources extends \Ctrl\Permissions\Resources
{
    const SET_ROUTES = 'Ctrl.Module.Auth.routes';
    const SET_ACTIONS = 'Ctrl.Module.Auth.actions';

    const ACTION_USER_REMOVE = 'User.remove';

    public function getSets()
    {
        return array_merge(
            parent::getSets(),
            array(
                self::SET_ROUTES,
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
                    'Ctrl\Module\Auth\Controller' => array(
                        'Index' => array(
                            'index',
                            'auth',
                        ),
                        'User' => array(
                            'index',
                            'edit',
                        ),
                        'User' => array(
                            'index',
                            'edit',
                        ),
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
