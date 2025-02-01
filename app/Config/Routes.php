<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// functions
$routes->post('auth','Home::Auth');
$routes->get('logout','Home::logout');
// other pages
$routes->get('sign-up','Home::signUp');
// fetch using ajax
$routes->get('fetch-cluster','ActionController::fetchCluster');
$routes->get('fetch-subject','ActionController::fetchSubject');
$routes->get('fetch-school-data','ActionController::fetchSchoolData');
$routes->get('school-data','ActionController::schoolData');
$routes->get('user-request','ActionController::userRequest');
$routes->get('review','ActionController::reviewRequest');
$routes->get('total-review','ActionController::totalReview');
$routes->get('action','ActionController::action');
// save using ajax
$routes->post('save-cluster','ActionController::saveCluster');
$routes->post('edit-cluster','ActionController::editCluster');
$routes->post('save-subject','ActionController::saveSubject');
$routes->post('edit-subject','ActionController::editSubject');
$routes->post('save-school','ActionController::saveSchool');
$routes->post('edit-school','ActionController::editSchool');
$routes->post('save','ActionController::save');
$routes->post('edit','ActionController::edit');
$routes->post('save-password','ActionController::savePassword');
$routes->post('reset-password','ActionController::resetPassword');
$routes->post('save-form','ActionController::saveForm');

$routes->group('',['filter'=>'AlreadyLoggedIn'],function($routes)
{
    $routes->get('/', 'Home::index');
});

$routes->group('',['filter'=>'AuthCheck'],function($routes)
{
    //admin
    $routes->get('/overview','Home::adminDashboard');
    $routes->get('/technical-assistance','Home::techAssistance');
    $routes->get('/user-accounts','Home::userAccounts');
    $routes->get('/new-account','Home::newAccount');
    $routes->get('/edit-account/(:any)','Home::editAccount/$1');
    $routes->get('/cluster-and-schools','Home::clusterAndSchools');
    $routes->get('/reports','Home::reports');
    $routes->get('/account','Home::myAccount');
    //manager
    $routes->get('/manager/overview','Home::managerDashboard');
    $routes->get('/manager/technical-assistance','Home::managerTechnicalAssistance');
    $routes->get('/manager/reports','Home::managerReport');
    //user
    $routes->get('/user/overview','Home::userDashboard');
    $routes->get('/user/technical-assistance','Home::userTechnicalAssistance');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
