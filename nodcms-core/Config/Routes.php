<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2020.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2020 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace Config;
// Create a new instance of our RouteCollection class.

$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
    require_once SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('NodCMS\Core\Controllers');
$routes->setDefaultController('Dispatcher');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Dispatcher::index');

// Admin URLs
$routes->get('admin', "General_admin::dashboard");
$routes->get('admin/(.+)', "General_admin::$1");
$routes->get('admin-provider', "Providers_admin::index");
$routes->get('admin-provider/(.+)', "Providers_admin::$1");

$routes->get('get-new-captcha', "General::resetCaptcha");

$routes->get('user/dashboard', "General_members::dashboard");
$routes->get('user/account', "General_members::account");
$routes->match(['post', 'get'],'user/account/personal-info', "General_members::accountPersonalInfo");
$routes->match(['post', 'get'],'user/account/change-password', "General_members::accountChangePassword");
$routes->match(['post', 'get'],'user/account/remove-avatar', "General_members::accountRemoveAvatar");
$routes->match(['post', 'get'],'user/account/remove-avatar-confirmed', "General_members::accountRemoveAvatar/1");

$routes->match(['post', 'get'],'user/account-avatar-change', "General_members::accountAvatarChange");
$routes->match(['post', 'get'],'user/account-avatar-upload', "General_members::accountAvatarUpload");
//$routes->get('user-([A-Za-z\_]+)/dashboard', '$1/dashboard');
//General URLs
$routes->get('(file|image)-([0-9]+)-([A-Za-z0-9\_]+)', 'General::$1/$2/$3');
$routes->get('noimage-([0-9]+)-([0-9]+)-([A-Za-z0-9\_]+)', 'General::noimage/$1/$2/$3');
$routes->get('noimage-([0-9]+)-([0-9]+)', 'General::noimage/$1/$2');
$routes->match(['post', 'get'],'remove-my-file/([0-9]+)-([A-Za-z0-9\_]+)', 'General::removeMyFile/$1/$2');
// General Pages
$routes->get('{locale}', 'General::index');
$routes->get('{locale}/([A-Za-z\_]+)-index', '$1::index');
$routes->match(['post', 'get'],'{locale}/contact', 'General::contact');
$routes->get('{locale}/contact-home', 'General::contact/home');
$routes->get('{locale}/(terms-and-conditions|privacy-policy)', "General::staticSettingsPages/$1");
// Registration
$routes->match(['post', 'get'],'admin-sign', "Registration::login");
$routes->get('account-locked', "Registration::accountLocked");
$routes->get('{locale}/account-locked', "Registration::accountLocked");
$routes->get('logout', "Registration::logout");
$routes->get('{locale}/logout', "Registration::logout");
$routes->match(['post', 'get'],'{locale}/user-registration', "Registration::userRegistration");
$routes->match(['post', 'get'],'{locale}/return-password', "Registration::returnPassword");
$routes->get('{locale}/user-registration/message', "Registration::userRegistrationMessage");
$routes->match(['post', 'get'],'{locale}/user-registration/active/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)', "Registration::activeAccount/$1/$2/");
$routes->match(['post', 'get'],'{locale}/set-new-password/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)', "Registration::setNewPassword/$1/$2/");

/**
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}