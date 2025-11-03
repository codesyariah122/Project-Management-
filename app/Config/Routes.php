<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Project management
$routes->get('/projects', 'ProjectController::index');
$routes->get('/projects/create', 'ProjectController::create');
$routes->post('/projects/store', 'ProjectController::store');
$routes->get('/projects/edit/(:num)', 'ProjectController::edit/$1');
$routes->post('/projects/update/(:num)', 'ProjectController::update/$1');
$routes->get('/projects/delete/(:num)', 'ProjectController::delete/$1');
$routes->get('/projects/timeline/(:num)', 'ProjectController::timeline/$1');
$routes->get('/projects/exportExcel/(:num)', 'ProjectController::exportExcel/$1');

// Template management
$routes->get('/templates', 'ProjectController::listTemplates'); // list semua template
$routes->get('/templates/create', 'ProjectController::createTemplate');
$routes->post('/templates/store', 'ProjectController::storeTemplate');
$routes->get('/templates/edit/(:num)', 'ProjectController::editTemplate/$1');
$routes->post('/templates/update/(:num)', 'ProjectController::updateTemplate/$1');
$routes->get('/templates/delete/(:num)', 'ProjectController::deleteTemplate/$1');


// Timeline Project
$routes->post('/projects/updateTimeline', 'ProjectController::updateTimeline');
$routes->get('/projects/team/(:num)', 'ProjectController::team/$1');

// Team management
$routes->get('/projects/team/add/(:num)', 'ProjectController::addTeam/$1');
$routes->post('/projects/team/store', 'ProjectController::storeTeam');

// Role management
$routes->get('/roles', 'ProjectRoleController::index');
$routes->get('/roles/add', 'ProjectRoleController::create');
$routes->post('/roles/store', 'ProjectRoleController::store');
$routes->get('/roles/edit/(:num)', 'ProjectRoleController::edit/$1');
$routes->post('/roles/update/(:num)', 'ProjectRoleController::update/$1');
$routes->get('/roles/delete/(:num)', 'ProjectRoleController::delete/$1');
