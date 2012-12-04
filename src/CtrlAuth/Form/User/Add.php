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

class Add extends \Ctrl\Form\Form
{
    const ELEM_USERNAME = 'username';
    const ELEM_PASSWORD = 'password';
    const ELEM_PASSWORD_CONFIRM = 'password_confirm';
    const ELEM_EMAIL = 'email';

    public function __construct($name = null)
    {
        parent::__construct('user-add');

        $input = new TextInput(self::ELEM_USERNAME);
        $input->setLabel('login');
        $this->add($input);

        $input = new TextInput(self::ELEM_PASSWORD);
        $input->setAttribute('type', 'password');
        $input->setLabel('password');
        $this->add($input);

        $input = new TextInput(self::ELEM_PASSWORD_CONFIRM);
        $input->setAttribute('type', 'password');
        $input->setLabel('confirm password');
        $this->add($input);

        $input = new TextInput(self::ELEM_EMAIL);
        $input->setLabel('email');
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
            'name'     => self::ELEM_PASSWORD,
            'required' => true,
        )))->add($factory->createInput(array(
            'name'     => self::ELEM_PASSWORD_CONFIRM,
            'required' => true,
        )))->add($factory->createInput(array(
            'name'     => self::ELEM_EMAIL,
            'required' => true,
            'validators' => array(
                array('name' => 'Zend\Validator\EmailAddress'),
            ),
        )));

        return $filter;
    }
}
