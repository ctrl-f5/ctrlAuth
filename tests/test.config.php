<?php

return array(
    'phpSettings' => array(
        //'display_errors' => 'On',
    ),
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'CtrlAuth',
        'Ctrl',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './vendor',
            'Ctrl' => './vendor/ctrl-f5/ctrllib/',
            'CtrlAuth' => './vendor/ctrl-f5/ctrlAuth/',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'view_manager' => array(
        'display_exceptions'       => true,
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);
