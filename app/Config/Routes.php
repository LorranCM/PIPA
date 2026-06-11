<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

#--------------------------------------------------------------------
#   1 - Home (não logado)
#--------------------------------------------------------------------

$routes->group('/', function($routes) {
    $routes->get('welcome', 'HomeController::welcome', ['as' => 'welcome', 'filter' => 'verifyLogin']);
    $routes->get('', 'HomeController::index',          ['as' => 'index']);

    $routes->get('user_management', 'HomeController::user_management', ['as' => 'user_management', 'filter' => 'verifyLogout']);
});

#--------------------------------------------------------------------
#   1.1 - Login e validação
#--------------------------------------------------------------------

$routes->group('auth', ['filter' => 'verifyLogin'], function($routes) {
    $routes->get('login', 'AuthController::login',                       ['as' => 'login']);
    $routes->get('recovery', 'AuthController::recovery',                 ['as' => 'recovery']);
    $routes->post('login/validation', 'AuthController::validation',      ['as' => 'validation']);
});

$routes->get('auth/logout', 'AuthController::logout', ['as' => 'logout']);

#--------------------------------------------------------------------
#   2 - Home (logado)
#--------------------------------------------------------------------
$routes->group('my', ['filter' => 'verifyLogout'], function($routes) {
    $routes->get('', 'HomeController::home', ['as' => 'home']);
#--------------------------------------------------------------------
#   2.1 - Student
#--------------------------------------------------------------------



#--------------------------------------------------------------------
#   2.1 - Teacher
#--------------------------------------------------------------------



#--------------------------------------------------------------------
#   2.1 - Admin
#--------------------------------------------------------------------

});