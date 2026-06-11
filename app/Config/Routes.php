<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'GuestController::index', ['as' => 'home']);

$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login',                       ['as' => 'login']);
    $routes->get('recovery', 'AuthController::recovery',                 ['as' => 'recovery']);
    $routes->post('login/validation', 'AuthController::validation',      ['as' => 'validation']);
    $routes->post('logout', 'AuthController::logout',                    ['as' => 'logout']);
});
