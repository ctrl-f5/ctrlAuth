<?php

namespace CtrlAuth\Form\User;

use \CtrlAuth\Domain;
use Ctrl\Form\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Zend\InputFilter\InputFilter;
use Ctrl\Form\Element\Hidden as HiddenInput;
use Ctrl\Form\Element\Text as TextInput;
use Ctrl\Form\Element\Textarea as TextareaInput;
use Ctrl\Form\Element\Select as SelectInput;

class Login extends \Ctrl\Form\Form
{
    const ELEM_USERNAME = 'username';
    const ELEM_PASSWORD = 'password';

    public function __construct($name = null)
    {
        parent::__construct('user-login');

        $input = new TextInput(self::ELEM_USERNAME);
        $input->setLabel('login');
        $this->add($input);

        $input = new TextInput(self::ELEM_PASSWORD);
        $input->setAttribute('type', 'password');
        $input->setLabel('password');
        $this->add($input);

        $this->setInputFilter($this->getInputFilter());
    }

    public function getInputFilter()
    {
        $factory = new FilterFactory();
        $filter = new InputFilter();
        $filter->add($factory->createInput(array(
            'name'     => 'username',
            'required' => true,
        )))->add($factory->createInput(array(
            'name'     => 'password',
            'required' => true,
        )));

        return $filter;
    }
}
