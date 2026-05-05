<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth routes (public)
$routes->get('/',                 'AuthController::login');
$routes->get('auth/login',        'AuthController::login');
$routes->post('auth/login',       'AuthController::doLogin');
$routes->get('auth/logout',       'AuthController::logout');

// Dashboard (semua role yang sudah login)
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'DashboardController::index');
});

// Admin & Superadmin only
$routes->group('unit-types', ['filter' => 'auth:admin,superadmin'], function($routes) {
    $routes->get('/',         'UnitTypeController::index');
    $routes->post('store',    'UnitTypeController::store');
    $routes->post('update/(:num)', 'UnitTypeController::update/$1');
    $routes->get('delete/(:num)', 'UnitTypeController::delete/$1');
});

// Superadmin only
$routes->group('users', ['filter' => 'auth:superadmin'], function($routes) {
    $routes->get('/',         'UserController::index');
    $routes->post('store',    'UserController::store');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
});

// Map settings (superadmin only)
$routes->group('map-settings', ['filter' => 'auth:superadmin'], function($routes) {
    $routes->get('/',              'MapController::index');
    $routes->post('upload',        'MapController::upload');
    $routes->get('set-active/(:num)', 'MapController::setActive/$1');
    $routes->get('delete/(:num)',  'MapController::delete/$1');
});

// API publik (semua yang login)
$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('map/active', 'MapController::getActive');
});

// API publik (semua yang login)
$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('map/active',       'MapController::getActive');
    $routes->get('unit-types',       'UnitTypeController::getAll'); // ← tambahkan ini
});

$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('map/active',              'MapController::getActive');
    $routes->get('unit-types',              'UnitTypeController::getAll');

    // Unit CRUD
    $routes->get('units',                   'UnitController::getAll');
    $routes->post('units/store',            'UnitController::store');
    $routes->post('units/update-pos/(:num)','UnitController::updatePosition/$1');
    $routes->post('units/update/(:num)',    'UnitController::update/$1');
    $routes->post('units/delete/(:num)',    'UnitController::delete/$1');

    // History
    $routes->get('units/history',           'UnitController::getHistory');
    $routes->get('history', 'HistoryController::getHistory');
});

// History (semua role yang login)
$routes->group('history', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'HistoryController::index');
});

// Superadmin only
$routes->group('users', ['filter' => 'auth:superadmin'], function($routes) {
    $routes->get('/',                      'UserController::index');
    $routes->post('store',                 'UserController::store');
    $routes->post('update/(:num)',         'UserController::update/$1');
    $routes->get('delete/(:num)',          'UserController::delete/$1');
    $routes->post('reset-password/(:num)', 'UserController::resetPassword/$1');
});