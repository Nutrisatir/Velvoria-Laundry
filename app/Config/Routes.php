<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public / Guest Routes
$routes->get('/', 'Guest\HomeController::index');
$routes->get('layanan', 'Guest\ServiceController::index');
$routes->get('harga', 'Guest\ServiceController::prices');
$routes->get('cara-kerja', 'Guest\PageController::howItWorks');
$routes->get('tentang', 'Guest\PageController::about');
$routes->get('kontak', 'Guest\PageController::contact');
$routes->post('kontak', 'Guest\PageController::sendContact');
$routes->get('faq', 'Guest\PageController::faq');
$routes->get('terms', 'Guest\PageController::terms');

// Authentication Routes (with guest filter so logged-in users don't see login/register)
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'Auth\LoginController::index');
    $routes->post('login', 'Auth\LoginController::attempt');
    $routes->get('register', 'Auth\RegisterController::index');
    $routes->post('register', 'Auth\RegisterController::store');
    $routes->get('forgot-password', 'Auth\PasswordController::forgotIndex');
    $routes->post('forgot-password', 'Auth\PasswordController::sendReset');
    $routes->get('reset-password/(:segment)', 'Auth\PasswordController::resetIndex/$1');
    $routes->post('reset-password', 'Auth\PasswordController::updatePassword');
});

// Logout
$routes->get('logout', 'Auth\LoginController::logout');
$routes->post('logout', 'Auth\LoginController::logout');

// Payment Webhook & Return URLs
$routes->post('payment/midtrans/notification', 'Payment\MidtransNotificationController::handle');
$routes->get('payment/finish', 'Payment\PaymentReturnController::finish');
$routes->get('payment/unfinish', 'Payment\PaymentReturnController::unfinish');
$routes->get('payment/error', 'Payment\PaymentReturnController::error');

// Customer Routes (Protected with 'auth:customer')
$routes->group('customer', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('dashboard', 'Customer\DashboardController::index');

    // Orders
    $routes->get('orders', 'Customer\OrderController::index');
    $routes->get('history', 'Customer\OrderController::history');
    $routes->get('help', 'Customer\HelpController::index');
    $routes->get('orders/create', 'Customer\OrderController::create');
    $routes->post('orders', 'Customer\OrderController::store');
    $routes->get('orders/(:segment)/receipt', 'Customer\OrderController::receipt/$1');
    $routes->get('orders/(:segment)', 'Customer\OrderController::show/$1');
    $routes->post('orders/(:segment)/cancel', 'Customer\OrderController::cancel/$1');
    $routes->post('orders/(:segment)/pay', 'Customer\OrderController::pay/$1');

    // Addresses
    $routes->get('addresses', 'Customer\AddressController::index');
    $routes->get('addresses/create', 'Customer\AddressController::create');
    $routes->post('addresses', 'Customer\AddressController::store');
    $routes->get('addresses/(:num)/edit', 'Customer\AddressController::edit/$1');
    $routes->post('addresses/(:num)', 'Customer\AddressController::update/$1');
    $routes->post('addresses/(:num)/delete', 'Customer\AddressController::delete/$1');
    $routes->post('addresses/(:num)/primary', 'Customer\AddressController::setPrimary/$1');

    // Profile
    $routes->get('profile', 'Customer\ProfileController::index');
    $routes->post('profile', 'Customer\ProfileController::update');
    $routes->post('profile/password', 'Customer\ProfileController::changePassword');
});

// Admin Routes (Protected with 'auth:admin')
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Orders Management
    $routes->get('orders', 'Admin\OrderController::index');
    $routes->get('orders/(:num)', 'Admin\OrderController::show/$1');
    $routes->post('orders/(:num)/status', 'Admin\OrderController::updateStatus/$1');
    $routes->post('orders/(:num)/weight', 'Admin\OrderController::updateWeight/$1');
    $routes->post('orders/(:num)/note', 'Admin\OrderController::addNote/$1');
    $routes->get('orders/(:num)/receipt', 'Admin\OrderController::printReceipt/$1');

    // Services Management
    $routes->get('services', 'Admin\ServiceController::index');
    $routes->get('services/create', 'Admin\ServiceController::create');
    $routes->post('services', 'Admin\ServiceController::store');
    $routes->get('services/(:num)/edit', 'Admin\ServiceController::edit/$1');
    $routes->post('services/(:num)', 'Admin\ServiceController::update/$1');
    $routes->post('services/(:num)/toggle', 'Admin\ServiceController::toggleActive/$1');

    // Service Categories
    $routes->get('service-categories', 'Admin\CategoryController::index');
    $routes->post('service-categories', 'Admin\CategoryController::store');
    $routes->post('service-categories/(:num)', 'Admin\CategoryController::update/$1');
    $routes->post('service-categories/(:num)/toggle', 'Admin\CategoryController::toggleActive/$1');

    // Customers
    $routes->get('customers', 'Admin\CustomerController::index');
    $routes->get('customers/(:num)', 'Admin\CustomerController::show/$1');
    $routes->post('customers/(:num)/toggle', 'Admin\CustomerController::toggleStatus/$1');

    // Payments
    $routes->get('payments', 'Admin\PaymentController::index');
    $routes->get('payments/(:num)', 'Admin\PaymentController::show/$1');
    $routes->post('payments/(:num)/mark-paid', 'Admin\PaymentController::markPaid/$1');

    // Promotions
    $routes->get('promotions', 'Admin\PromotionController::index');
    $routes->get('promotions/create', 'Admin\PromotionController::create');
    $routes->post('promotions', 'Admin\PromotionController::store');
    $routes->get('promotions/(:num)/edit', 'Admin\PromotionController::edit/$1');
    $routes->post('promotions/(:num)', 'Admin\PromotionController::update/$1');
    $routes->post('promotions/(:num)/toggle', 'Admin\PromotionController::toggleActive/$1');

    // Reports
    $routes->get('reports', 'Admin\ReportController::index');
    $routes->get('reports/export-csv', 'Admin\ReportController::exportCsv');

    // Store Settings
    $routes->get('settings', 'Admin\SettingController::index');
    $routes->post('settings', 'Admin\SettingController::update');

    // Activity logs
    $routes->get('activity-logs', 'Admin\ActivityLogController::index');

    // Staff
    $routes->get('staff', 'Admin\StaffController::index');
    $routes->post('staff', 'Admin\StaffController::store');
    $routes->post('staff/(:num)/toggle', 'Admin\StaffController::toggleStatus/$1');
});
