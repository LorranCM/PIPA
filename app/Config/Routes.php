<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->set404Override('App\Controllers\HomeController::error404');

#--------------------------------------------------------------------
#   1 - Home (não logado)
#--------------------------------------------------------------------

$routes->group('/', function($routes) {
    $routes->get('welcome', 'HomeController::welcome', ['as' => 'welcome', 'filter' => 'verifyLogin']);
    $routes->get('', 'HomeController::index',          ['as' => 'index']);
});

#--------------------------------------------------------------------
#   1.1 - Login e validação
#--------------------------------------------------------------------

$routes->group('auth', ['filter' => 'verifyLogin'], function($routes) {
    $routes->get('login', 'AuthController::login',                  ['as' => 'login']);
    $routes->get('recovery', 'AuthController::recovery',            ['as' => 'recovery']);
    $routes->post('login/validation', 'AuthController::validation', ['as' => 'validation']);
});

$routes->get('auth/logout', 'AuthController::logout', ['as' => 'logout']);

#--------------------------------------------------------------------
#   2 - Usuário (logado)
#--------------------------------------------------------------------

$routes->group('requires', ['filter' => 'verifyLogout'], function($routes) {

    $routes->get('refresh_self_data', 'HomeController::refresh_user_data',                         ['as' => 'refresh_user_data']);
    $routes->post('get_calendar_data', 'CalendarController::get_user_calendar_data',                ['as' => 'get_self_calendar_data']);
    $routes->post('get_calendar_data/(:alphanum)', 'CalendarController::get_user_calendar_data/$1', ['as' => 'get_calendar_data']);

});

$routes->group('my', ['filter' => 'verifyLogout'], function($routes) {
    
    $routes->get('', 'HomeController::user_home_management', ['as' => 'home']);

#--------------------------------------------------------------------
#   2.1 - Student
#--------------------------------------------------------------------

    $routes->get('student', 'StudentController::index', ['as' => 'student_home', 'filter' => 'preventNonRolePage']);

#--------------------------------------------------------------------
#   2.1 - Teacher
#--------------------------------------------------------------------

    $routes->get('teacher', 'TeacherController::index', ['as' => 'teacher_home', 'filter' => 'preventNonRolePage']);

#--------------------------------------------------------------------
#   2.1 - Admin
#--------------------------------------------------------------------

    $routes->get('admin', 'AdminController::index', ['as' => 'admin_home', 'filter' => 'preventNonRolePage']);

});