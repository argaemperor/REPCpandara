<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Dashboard & Event
$routes->get('Admin/Dashboard', 'Admin::Dashboard', ['filter' => 'auth:1']);
$routes->get('Admin/Event', 'Admin::Event', ['filter' => 'auth:1']);
$routes->get('admin/Event/ajaxEventList', 'Admin::ajaxEventList', ['filter' => 'auth:1']);
$routes->post('admin/event/save', 'Admin::saveEvent', ['filter' => 'auth:1']);
$routes->post('admin/event/toggle/(:num)', 'Admin::toggleEventStatus/$1', ['filter' => 'auth:1']);
$routes->post('admin/event/update', 'Admin::update', ['filter' => 'auth:1']);
$routes->post('admin/event/delete/(:num)', 'Admin::delete/$1', ['filter' => 'auth:1']);

// Master User
$routes->get('/users', 'Admin::MasterUser', ['filter' => 'auth:1']);
$routes->post('/users/save', 'Admin::MasterUsersave', ['filter' => 'auth:1']);
$routes->get('admin/ajaxMasterUserList', 'Admin::ajaxMasterUserList', ['filter' => 'auth:1']);
$routes->post('admin/delete-user/(:num)', 'Admin::deleteUser/$1', ['filter' => 'auth:1']);
$routes->post('admin/users/delete/(:num)', 'Admin::deleteUser/$1', ['filter' => 'auth:1']);
$routes->post('admin/update-user', 'Admin::updateUser', ['filter' => 'auth:1']);

// Participant Management
$routes->get('/participants', 'participant::index', ['filter' => 'auth:1']);
$routes->get('participant/ajaxList', 'Participant::ajaxList', ['filter' => 'auth:1']);
$routes->get('participant/edit/(:num)', 'Participant::edit/$1', ['filter' => 'auth:1']);
$routes->post('participant/update/(:num)', 'Participant::update/$1', ['filter' => 'auth:1']);
$routes->post('participant/update-field', 'Participant::updateField', ['filter' => 'auth:1']);

//Event Manager
$routes->get('EventMgr/Dashboard', 'EventManager::Dashboard', ['filter' => 'auth:2']);
$routes->get('EventMgr/participant', 'EventManager::participantmgr', ['filter' => 'auth:2']);
$routes->get('EventMgr/participantCO', 'EventManager::participantmgrCheckout', ['filter' => 'auth:2']);
$routes->get('EventManager/ajaxList', 'EventManager::ajaxListparticipantmgr', ['filter' => 'auth:2']);
$routes->get('EventManager/ajaxListChecout', 'EventManager::ajaxListparticipantmgrCheckOut', ['filter' => 'auth:2']);
$routes->post('EventManager/checkoutMultiple', 'EventManager::checkoutMultiple', ['filter' => 'auth:2']);
$routes->match(['get', 'post'], 'EventManager/checkoutScan', 'EventManager::checkoutScan'); // form tampilan scan
$routes->post('EventManager/processCheckoutScan', 'EventManager::processCheckoutScan'); // proses final
$routes->get('EventMgr/Event', 'EventManager::Event', ['filter' => 'auth:2']);
$routes->get('EventManager/ajaxEventList', 'EventManager::ajaxEventListManager', ['filter' => 'auth:2']);
$routes->post('EventManager/saveEventMgr', 'EventManager::saveEvent', ['filter' => 'auth:2']);
$routes->post('EventManager/toggle/(:num)', 'EventManager::toggleEventStatusmgr/$1', ['filter' => 'auth:2']);
$routes->post('EventManager/update', 'EventManager::updateEventmgr', ['filter' => 'auth:2']);
$routes->post('EventManager/delete/(:num)', 'EventManager::deleteMgr/$1', ['filter' => 'auth:2']);
$routes->get('EventManager/users', 'EventManager::MasterUserMgr', ['filter' => 'auth:2']);
//$routes->post('/users/save', 'Admin::MasterUsersave', ['filter' => 'auth:1']);
$routes->get('EventManager/ajaxMasterUserList', 'EventManager::ajaxMasterUserListmgr', ['filter' => 'auth:2']);
$routes->post('EventManager/update', 'EventManager::updateUserMgr', ['filter' => 'auth:2']);
$routes->post('EventManager/delete/(:num)', 'EventManager::deleteUserMgr/$1', ['filter' => 'auth:2']);

//$routes->get('EventMgr/ajaxList', 'EventManager::ajaxListparticipantmgr', ['filter' => 'auth:2']);



//Operator
$routes->get('Operator/Dashboard', 'Operator::dashboard', ['filter' => 'auth:3']);
$routes->get('Operator/participant', 'Operator::participantOperator', ['filter' => 'auth:3']);
$routes->get('Operator/search-participant', 'Operator::searchParticipant', ['filter' => 'auth:3']);
$routes->get('operator/search-ajax', 'Operator::searchParticipantAjax', ['filter' => 'auth:3']);
$routes->post('operator/checkout', 'Operator::checkout', ['filter' => 'auth:3']);
$routes->get('operator/getParticipantsAjax', 'Operator::getParticipantsAjax', ['filter' => 'auth:3']);

// Auth
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::showRegister');
$routes->post('register', 'Auth::register');
$routes->post('check-id', 'Auth::checkID');



// Event selection (public access, perhaps at login)
$routes->get('select-event', 'Admin::selectEvent');
$routes->get('admin/select-event', 'Admin::selectEvent');
$routes->post('admin/select-event/save', 'Admin::saveEventSelection');

// Other
$routes->get('participant/jersey-options', 'Participant::getJerseyOptions');
$routes->post('participant/update', 'Participant::update');
$routes->post('operator/checkout-start', 'Operator::checkoutStart');
