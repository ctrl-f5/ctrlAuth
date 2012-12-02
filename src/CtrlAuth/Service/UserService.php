<?php

namespace CtrlAuth\Service;

use \CtrlAuth\Domain;
use CtrlAuth\Domain\User;

class UserService extends \Ctrl\Service\AbstractDomainModelService
{
    const EVENT_ON_LOGIN = 'on.login';
    const EVENT_ON_LOGOUT = 'on.logout';

    protected $entity = 'CtrlAuth\Domain\User';

    protected $guestUser;

    public function getForm(User $user = null)
    {
        $form = new \CtrlAuth\Form\User\Edit('article');
        if ($user) $form->loadModel($user);

        return $form;
    }

    public function getLoginForm()
    {
        $form = new \CtrlAuth\Form\User\Login('user-login');

        return $form;
    }

    public function authenticate($username, $password)
    {
        $user = $this->getByUserName($username);
        if ($user->authenticate($password)) {
            if ($this->saveAuth($user)) {
                $this->getEventManager()->trigger(self::EVENT_ON_LOGIN, $this, array('user' => $user));
            }
        }
    }

    public function resetAuthentication()
    {
        $user = $this->getAuthenticatedUser();
        if ($user->getUsername() != 'guest') {
            if ($this->removeAuth($user)) {
                $this->getEventManager()->trigger(self::EVENT_ON_LOGOUT, $this, array('user' => $user));
            }
        }
    }

    public function saveAuth(User $user)
    {
        $session = new \Zend\Session\Container('ctrl_module_auth');
        $session->offsetSet('auth.authenticated', 1);
        $session->offsetSet('auth.user', $user->getId());
        return true;
    }

    public function removeAuth(User $user)
    {
        $session = new \Zend\Session\Container('ctrl_module_auth');
        $session->offsetSet('auth.authenticated', 0);
        $session->offsetSet('auth.user', 0);
        return true;
    }

    /**
     * return the logged in user
     *
     * @return bool|User
     */
    public function getAuthenticatedUser()
    {
        $session = new \Zend\Session\Container('ctrl_module_auth');
        if ($session->offsetGet('auth.authenticated')) {
            return $this->getById($session->offsetGet('auth.user'));
        } else {
            return $this->getGuestUser();
        }
    }

    /**
     * @param \CtrlAuth\Domain\User $user
     * @return bool
     */
    public function isAuthenticatedUser(User $user)
    {
        $session = new \Zend\Session\Container('ctrl_module_auth');
        if ($session->offsetGet('auth.authenticated') == $user->getId()) {
            return true;
        }
        return false;
    }

    /**
     * @param $username
     * @return User
     * @throws Exception
     */
    public function getByUserName($username)
    {
        $entities = $this->getEntityManager()
            ->createQuery('SELECT e FROM '.$this->entity.' e WHERE e.username = :username')
            ->setParameter('username', $username)
            ->getResult();
        if (!count($entities)) {
            throw new Exception($this->entity.' not found with username: '.$username);
        }
        return $entities[0];
    }

    public function getGuestUser()
    {
        if (!$this->guestUser) {
            $guest = new \CtrlAuth\Domain\GuestUser();
            $guest->setUsername('guest');
            $guest->setServiceLocator($this->getServiceLocator());
            $roleService = $this->getDomainService('CtrlAuthRole');
            $guest->setRoles($roleService->getGuestRoles());
            $this->guestUser = $guest;
        }
        return $this->guestUser;
    }
}
