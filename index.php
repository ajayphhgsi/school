<?php
/**
 * School Management System - Main Entry Point
 * Version 1.0.0
 */

// Start session
session_start();

// Define constants
define('BASE_PATH', __DIR__ . '/');
define('APP_PATH', BASE_PATH . 'app/');
define('CORE_PATH', BASE_PATH . 'core/');
define('CONFIG_PATH', BASE_PATH . 'config/');
define('CONTROLLERS_PATH', BASE_PATH . 'controllers/');
define('MODELS_PATH', BASE_PATH . 'models/');
define('VIEWS_PATH', BASE_PATH . 'views/');
define('UPLOADS_PATH', BASE_PATH . 'uploads/');

// Include autoloader (if using Composer)
if (file_exists(BASE_PATH . 'vendor/autoload.php')) {
    require_once BASE_PATH . 'vendor/autoload.php';
}

// Include core files
require_once CORE_PATH . 'Database.php';
require_once CORE_PATH . 'Router.php';
require_once CORE_PATH . 'Security.php';
require_once CORE_PATH . 'Session.php';
require_once CORE_PATH . 'Validator.php';

// Include base controller
require_once CONTROLLERS_PATH . 'Controller.php';

// Include middleware
require_once BASE_PATH . 'middleware/Auth.php';

// Load configuration
$config = require CONFIG_PATH . 'app.php';

// Initialize core components
$database = new Database();
$router = new Router();
$security = new Security();
$session = new Session();

// Define routes
// Public routes
$router->get('/', 'PublicController@index');
$router->get('/about', 'PublicController@about');
$router->get('/courses', 'PublicController@courses');
$router->get('/events', 'PublicController@events');
$router->get('/gallery', 'PublicController@gallery');
$router->get('/contact', 'PublicController@contact');
$router->post('/contact', 'PublicController@contact');

// Admin routes
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/students', 'AdminController@students');
$router->get('/admin/students/create', 'AdminController@createStudent');
$router->post('/admin/students', 'AdminController@storeStudent');
$router->get('/admin/students/edit/{id}', 'AdminController@editStudent');
$router->post('/admin/students/update/{id}', 'AdminController@updateStudent');
$router->get('/admin/students/delete/{id}', 'AdminController@deleteStudent');
$router->get('/admin/classes', 'AdminController@classes');
$router->get('/admin/attendance', 'AdminController@attendance');
$router->get('/admin/exams', 'AdminController@exams');
$router->get('/admin/fees', 'AdminController@fees');
$router->get('/admin/events', 'AdminController@events');
$router->get('/admin/gallery', 'AdminController@gallery');
$router->get('/admin/reports', 'AdminController@reports');
$router->get('/admin/settings', 'AdminController@settings');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@showForgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');

// Handle routing
$router->dispatch();
?>