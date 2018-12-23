<?php

require_once '' . ROOT . DS . 'libs' . DS . 'config.php';
Config::set('path', '/');
Config::set('default_route', 'index');
Config::set('default_action', 'index');
Config::set('default_controller', 'landing');
Config::set('routes', array(
    'default' => '',
    'adminlogin' => 'system_',
));
Config::set('routes_controller', array(
));
Config::set('Error_page', '/404.php');

Config::set('path_default', array(
    ''
));

Config::set('adminlogin', 'adminlogin'
);

Config::set('admin_page', array(
    'adminorder' => 'adminorder'
));
