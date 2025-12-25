<?php
/**
 * School Management System - Main Entry Point
 * Version 1.0.0
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
require_once BASE_PATH . 'middleware/Student.php';

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
$router->get('/admin/students/view/{id}', 'AdminController@viewStudent');
$router->get('/admin/students/edit/{id}', 'AdminController@editStudent');
$router->post('/admin/students/update/{id}', 'AdminController@updateStudent');
$router->get('/admin/students/delete/{id}', 'AdminController@deleteStudent');
$router->get('/admin/classes', 'AdminController@classes');
$router->get('/admin/classes/promote', 'AdminController@promoteStudents');
$router->post('/admin/classes/promote', 'AdminController@processPromotion');
$router->get('/admin/attendance', 'AdminController@attendance');
$router->get('/admin/attendance/data', 'AdminController@attendanceData');
$router->post('/admin/attendance/save', 'AdminController@saveAttendance');
$router->get('/admin/attendance/export', 'AdminController@exportAttendance');
$router->get('/admin/exams', 'AdminController@exams');
$router->get('/admin/exams/admit-cards', 'AdminController@admitCards');
$router->get('/admin/exams/{id}/students', 'AdminController@getExamStudents');
$router->post('/admin/exams/generate-admit-cards', 'AdminController@generateAdmitCards');
$router->post('/admin/exams/generate-admit-card', 'AdminController@generateAdmitCard');

// Certificate Management
$router->get('/admin/certificates', 'AdminController@certificates');
$router->get('/admin/certificates/students', 'AdminController@getCertificateStudents');
$router->post('/admin/certificates/generate', 'AdminController@generateCertificate');

// Marksheet Generation
$router->get('/admin/exams/create', 'AdminController@createExam');
$router->post('/admin/exams/store', 'AdminController@storeExam');
$router->get('/admin/exams/{id}/results', 'AdminController@enterResults');
$router->get('/admin/exams/{id}/existing-results', 'AdminController@getExistingResults');
$router->post('/admin/exams/save-results', 'AdminController@saveResults');
$router->get('/admin/exams/marksheets', 'AdminController@marksheets');
$router->get('/admin/exams/{id}/results/students', 'AdminController@getExamResultsStudents');
$router->post('/admin/exams/generate-marksheets', 'AdminController@generateMarksheets');
$router->post('/admin/exams/generate-marksheet', 'AdminController@generateMarksheet');
$router->get('/admin/fees', 'AdminController@fees');
$router->get('/admin/fees/create', 'AdminController@createFee');
$router->post('/admin/fees/store', 'AdminController@storeFee');
$router->get('/admin/fees/students', 'AdminController@getStudentsForFees');

// Expense Management
$router->get('/admin/expenses', 'AdminController@expenses');
$router->get('/admin/expenses/create', 'AdminController@createExpense');
$router->post('/admin/expenses/store', 'AdminController@storeExpense');
$router->get('/admin/expenses/export', 'AdminController@exportExpenses');
$router->get('/admin/events', 'AdminController@events');
$router->get('/admin/gallery', 'AdminController@gallery');
$router->get('/admin/reports', 'AdminController@reports');
$router->get('/admin/settings', 'AdminController@settings');

// Homepage Management
$router->get('/admin/homepage', 'AdminController@homepage');
$router->get('/admin/homepage/carousel', 'AdminController@homepageCarousel');
$router->post('/admin/homepage/carousel', 'AdminController@saveHomepageCarousel');
$router->get('/admin/homepage/about', 'AdminController@homepageAbout');
$router->post('/admin/homepage/about', 'AdminController@saveHomepageAbout');

// Student routes
$router->get('/student/dashboard', 'StudentController@dashboard');
$router->get('/student/profile', 'StudentController@profile');
$router->post('/student/profile', 'StudentController@updateProfile');
$router->get('/student/attendance', 'StudentController@attendance');
$router->get('/student/results', 'StudentController@results');
$router->get('/student/fees', 'StudentController@fees');
$router->get('/student/events', 'StudentController@events');
$router->get('/student/resources', 'StudentController@resources');
$router->get('/student/change-password', 'StudentController@changePassword');
$router->post('/student/change-password', 'StudentController@updatePassword');

// API routes
$router->post('/api/v1/auth/login', 'ApiController@login');
$router->get('/api/v1/students', 'ApiController@getStudents');
$router->get('/api/v1/students/{id}', 'ApiController@getStudent');
$router->get('/api/v1/fees', 'ApiController@getFees');
$router->get('/api/v1/students/{id}/fees', 'ApiController@getStudentFees');
$router->get('/api/v1/exams', 'ApiController@getExams');
$router->get('/api/v1/exams/{id}/results', 'ApiController@getExamResults');
$router->get('/api/v1/attendance', 'ApiController@getAttendance');
$router->get('/api/v1/reports', 'ApiController@getReports');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@showForgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');

// Handle routing
$router->dispatch();
?>