<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// $routes->set404Override('App\Controllers\HomeController::error404');

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

    $routes->get('refresh_self_data', 'HomeController::refresh_user_data', ['as' => 'refresh_user_data']);

    $routes->post('get_calendar_data', 'CalendarController::get_user_calendar_data', ['as' => 'get_self_calendar_data']);
    $routes->post('get_calendar_data/(:alphanum)', 'CalendarController::get_user_calendar_data/$1', ['as' => 'get_calendar_data']);
    $routes->post('get_calendar_data_by_Cid/(:alphanum)', 'CalendarController::get_calendar_data_by_classroom_id/$1', ['as' => 'get_calendar_data_by_classroom_id']);
    $routes->post('get_classrooms_basic_info', 'ClassroomController::get_classrooms_basic_info', ['as' => 'get_classrooms_basic_info']);
    $routes->post('get_documents/(:alphanum)', 'ClassroomController::get_documents/$1', ['as' => 'get_documents']);

    $routes->post('upload_document', 'ImgBBController::ulpoad_document', ['as' => 'upload_document']);
    $routes->post('delete_document', 'ImgBBController::delete_document', ['as' => 'delete_document']);
    $routes->post('cancel_event/(:alphanum)', 'EventController::cancel_event/$1', ['as' => 'cancel_event']);
    $routes->post('schedule_event/(:alphanum)/(:any)', 'EventController::schedule_event/$1/$2', ['as' => 'schedule_event']);
    $routes->post('confirm_event/(:alphanum)', 'EventController::confirm_event/$1', ['as' => 'confirm_event']);

});

$routes->group('my', ['filter' => 'verifyLogout'], function($routes) {
    
    $routes->get('', 'HomeController::user_home_management', ['as' => 'home']);

#--------------------------------------------------------------------
#   2.1 - Student
#--------------------------------------------------------------------

    $routes->get('student', 'StudentController::index', ['as' => 'student_home', 'filter' => 'preventNonRolePage']);

#--------------------------------------------------------------------
#   2.2 - Teacher
#--------------------------------------------------------------------

    $routes->get('teacher', 'TeacherController::index', ['as' => 'teacher_home', 'filter' => 'preventNonRolePage']);

#--------------------------------------------------------------------
#   2.3 - Admin
#--------------------------------------------------------------------

    $routes->get('admin', 'AdminController::index', ['as' => 'admin_home', 'filter' => 'preventNonRolePage']);

    $routes->get('admin/manage_users', 'AdminController::users', ['as' => 'admin_users']);
    $routes->get('admin/manage_user/(:alphanum)', 'AdminController::user/$1', ['as' => 'admin_user']);
    $routes->get('admin/manage_classroom/(:alphanum)', 'AdminController::classroom/$1', ['as' => 'admin_classroom']);
    $routes->get('admin/create_user_form/', 'AdminController::create_user_form', ['as' => 'admin_create_user_form']);
    $routes->get('admin/create_classroom_form/', 'AdminController::create_classroom_form', ['as' => 'admin_create_classroom_form']);

    $routes->post('admin/store_user/', 'AdminController::store_user', ['as' => 'store_user']);
    $routes->post('admin/store_classroom/', 'AdminController::store_classroom', ['as' => 'store_classroom']);
    $routes->post('admin/update_user/(:alphanum)', 'AdminController::update_user/$1', ['as' => 'update_user']);
    $routes->post('admin/delete_user/(:alphanum)', 'AdminController::delete_user/$1', ['as' => 'delete_user']);
    $routes->post('admin/update_classroom/(:alphanum)', 'AdminController::update_classroom/$1', ['as' => 'update_classroom']);
    $routes->post('admin/delete_classroom/(:alphanum)', 'AdminController::delete_classroom/$1', ['as' => 'delete_classroom']);
    $routes->get('admin/manage_classrooms', 'AdminController::classrooms', ['as' => 'admin_classrooms']);

#--------------------------------------------------------------------
#   2.4 - Busca
#--------------------------------------------------------------------

    $routes->get('search/classroom/(:alphanum)', 'ClassroomController::search_class/$1', ['as' => 'search_class']);

});