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

class PermissionService extends \Ctrl\Service\AbstractDomainModelService
{
    protected $entity = 'Ctrl\Module\Auth\Domain\Permission';
}
