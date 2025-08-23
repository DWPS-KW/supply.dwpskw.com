<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Employees');
$routes->setDefaultMethod('index');

$routes->get('/', 'Employees::index');

// Auth Routes
// $routes->get('login', 'Auth::login');
// $routes->post('doLogin', 'Auth::doLogin');
$routes->get('login', 'Auth::login', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'doLogin', 'Auth::doLogin');

$routes->get('logout', 'Auth::logout');


// attendance Routes
$routes->group('attendance', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Attendance::index');
    $routes->get('fingerPrintforEmp', 'Attendance::fingerPrintforEmp');
    $routes->get('fingerPrint', 'Attendance::fingerPrint');
    $routes->get('monthlyCoverList_form', 'Attendance::monthlyCoverList_form');
    $routes->post('monthlyCoverList_save', 'Attendance::monthlyCoverList_save');
    $routes->post('monthlyCoverList_ot_save', 'Attendance::monthlyCoverList_ot_save');
    $routes->get('monthlyCoverList', 'Attendance::monthlyCoverList');
    $routes->get('getEmpAttend', 'Attendance::getEmpAttend');
    $routes->get('fingerPrintForEmp', 'Attendance::fingerPrintForEmp');
    $routes->get('exportToExcel', 'Attendance::exportToExcel');
    $routes->get('test', 'Attendance::test');
});


// Employees Routes
$routes->group('employees', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Employees::index');
    $routes->get('index', 'Employees::index');
    $routes->get('ajaxSearch', 'Employees::ajaxSearch');
    $routes->get('show/(:num)', 'Employees::show/$1');

    $routes->get('new', 'Employees::new');
    $routes->post('create', 'Employees::create');
    $routes->get('edit/(:num)', 'Employees::edit/$1');
    $routes->post('update/(:num)', 'Employees::update/$1');

    $routes->get('searching', 'Employees::searching');
    $routes->get('exportToExcel', 'Employees::exportToExcel');
    $routes->get('exportToCSV', 'Employees::exportToCSV');
    $routes->post('upload', 'Employees::upload');
    $routes->get('delEmpPhoto/(:num)', 'Employees::delEmpPhoto/$1');
    $routes->delete('delete/(:num)', 'Employees::delete/$1');
    $routes->get('delete/(:num)', 'Employees::delete/$1');
});

// Sub Sec Routes
$routes->group('stnSubSec', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StnSubSec::browseLoadSubSec');
    $routes->get('create', 'StnSubSec::browseLoadSubSecEmpCreate');
    $routes->get('edit', 'StnSubSec::browseLoadSubSecEmpEdit');
    $routes->get('browseLoadSubSec', 'StnSubSec::browseLoadSubSec');
});


// Leaves Routes
$routes->group('leaves', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Leaves::index');
    $routes->get('browse', 'Leaves::index');
    $routes->post('create', 'Leaves::create');
    $routes->post('update', 'Leaves::update');
    $routes->post('delete', 'Leaves::delete');
    $routes->get('exportToExcel', 'Leaves::exportToExcel');

});

// Medicals Routes
$routes->group('medicals', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Medicals::index');
    $routes->get('browse', 'Medicals::index');
    $routes->post('create', 'Medicals::create');
    $routes->post('update', 'Medicals::update');
    $routes->post('delete', 'Medicals::delete');
    $routes->get('exportToExcel', 'Medicals::exportToExcel');
});

// Full Day Permissions Routes
$routes->group('fdays', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Fdays::index');
    $routes->post('create', 'Fdays::create');
    $routes->post('update', 'Fdays::update');
    $routes->post('delete', 'Fdays::delete');
    $routes->get('exportToExcel', 'Fdays::exportToExcel');
});

// Holidays Routes
$routes->group('holidays', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Holidays::index');
    $routes->post('create', 'Holidays::create');
    $routes->post('update', 'Holidays::update');
    $routes->post('delete', 'Holidays::delete');
});

$routes->group('excel', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('empsSearch', 'Excel::empsSearch');
    $routes->get('permissions', 'Excel::permissions');
    $routes->get('leaves', 'Excel::leaves');
    $routes->get('medicals', 'Excel::medicals');
});