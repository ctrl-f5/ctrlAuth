<?php

namespace Ctrl\Module\Auth\Form\User;

use \Ctrl\Module\Auth\Domain;
use Ctrl\Form\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Zend\InputFilter\InputFilter;
use Ctrl\Form\Element\Hidden as HiddenInput;
use Ctrl\Form\Element\Text as TextInput;
use Ctrl\Form\Element\Textarea as TextareaInput;
use Ctrl\Form\Element\Select as SelectInput;

class Edit extends \Ctrl\Form\Form
{
    const ELEM_USERNAME = 'username';
    const ELEM_PASSWORD_OLD = 'password_old';
    const ELEM_PASSWORD_NEW = 'password_new';

    public function __construct($name = null)
    {
        parent::__construct('user-edit');

        $input = new TextInput(self::ELEM_USERNAME);
        $input->setLabel('login');
        $this->add($input);

        $input = new TextInput(self::ELEM_PASSWORD_OLD);
        $input->setAttribute('type', 'password');
        $input->setLabel('current password');
        $this->add($input);

        $input = new TextInput(self::ELEM_PASSWORD_NEW);
        $input->setAttribute('type', 'password');
        $input->setLabel('new password');
        $this->add($input);

        $this->setInputFilter($this->getInputFilter());
    }

    public function getInputFilter()
    {
        $factory = new FilterFactory();
        $filter = new InputFilter();
        $filter->add($factory->createInput(array(
            'name'     => self::ELEM_USERNAME,
            'required' => true,
        )))->add($factory->createInput(array(
            'name'     => self::ELEM_PASSWORD_OLD,
            'required' => false,
        )))->add($factory->createInput(array(
            'name'     => self::ELEM_PASSWORD_NEW,
            'required' => false,
        )));

        return $filter;
    }

    public function loadModel(\Ctrl\Module\Auth\Domain\User $user)
    {
        $this->elements[self::ELEM_USERNAME]->setValue($user->getUsername());
    }
}
