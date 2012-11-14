<?php

namespace Ctrl\Module\Auth\Service;

use \Ctrl\Module\Auth\Domain;
use Ctrl\Module\Auth\Domain\User;
use Ctrl\Form\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Zend\InputFilter\InputFilter;
use Ctrl\Form\Element\Text as TextInput;
use Ctrl\Form\Element\Textarea as TextareaInput;
use Ctrl\Form\Element\Select as SelectInput;

class UserService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'Ctrl\Module\Auth\Domain\User';

    public function getForm(User $user = null)
    {
        $form = new \Ctrl\Module\Auth\Form\User\Edit('article');
        if ($user) $form->loadModel($user);

        return $form;
    }

    public function getLoginForm()
    {
        $form = new \Ctrl\Module\Auth\Form\User\Login('user-login');

        return $form;
    }

    public function getModelInputFilter(Article $article = null)
    {
        $factory = new FilterFactory();
        $filter = new InputFilter();
        $filter->add($factory->createInput(array(
            'name'     => 'title',
            'required' => true,
        )))->add($factory->createInput(array(
            'name'     => 'content',
            'required' => true,
        )))->add($factory->createInput(array(
            'name'     => 'content',
            'required' => true,
        )));

        return $filter;
    }

    public function authenticate($username, $password)
    {
        $user = $this->getByUserName($username);
        if ($user->authenticate($password)) {
            $this->saveAuth($user);
        }
    }

    public function saveAuth(User $user)
    {
        $session = new \Zend\Session\Container('ctrl_module_auth');
        $session->offsetSet('auth.authenticated', 1);
        $session->offsetSet('auth.user', $user->getId());
    }

    public function getAuthenticatedUser()
    {
        $session = new \Zend\Session\Container('ctrl_module_auth');
        if ($session->offsetGet('auth.authenticated')) {
            return $this->getById($session->offsetGet('auth.user'));
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
}
