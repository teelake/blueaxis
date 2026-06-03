<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\BlogController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\SeoController;
use App\Controllers\ServiceController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\BlogAdminController;
use App\Controllers\Admin\ContactAdminController;
use App\Controllers\Admin\ContentAdminController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\EmailSettingsController;
use App\Controllers\Admin\MediaAdminController;
use App\Controllers\Admin\QuoteAdminController;
use App\Controllers\Admin\ServiceAdminController;
use App\Core\Auth;
use App\Core\Router;

$router = new Router();

// Public routes
$router->get('/', [new HomeController(), 'index']);
$router->get('/about', [new AboutController(), 'index']);
$router->get('/services', [new ServiceController(), 'index']);
$router->get('/services/{slug}', [new ServiceController(), 'show']);
$router->get('/blog', [new BlogController(), 'index']);
$router->get('/blog/{slug}', [new BlogController(), 'show']);
$router->get('/contact', [new ContactController(), 'index']);
$router->post('/contact', [new ContactController(), 'submitContact']);
$router->post('/quote', [new ContactController(), 'submitQuote']);
$router->get('/sitemap.xml', [new SeoController(), 'sitemap']);
$router->get('/robots.txt', [new SeoController(), 'robots']);

// Admin auth
$router->get('/admin/login', [new AuthController(), 'loginForm']);
$router->post('/admin/login', [new AuthController(), 'login']);
$router->post('/admin/logout', [new AuthController(), 'logout']);

// Admin (protected via controller)
$router->get('/admin', [new DashboardController(), 'index']);
$router->get('/admin/dashboard', [new DashboardController(), 'index']);

$router->get('/admin/content/home', [new ContentAdminController(), 'editHome']);
$router->post('/admin/content/home', [new ContentAdminController(), 'saveHome']);
$router->get('/admin/content/about', [new ContentAdminController(), 'editAbout']);
$router->post('/admin/content/about', [new ContentAdminController(), 'saveAbout']);

$router->get('/admin/services', [new ServiceAdminController(), 'index']);
$router->get('/admin/services/create', [new ServiceAdminController(), 'create']);
$router->post('/admin/services', [new ServiceAdminController(), 'store']);
$router->get('/admin/services/{id}/edit', [new ServiceAdminController(), 'edit']);
$router->post('/admin/services/{id}', [new ServiceAdminController(), 'update']);
$router->post('/admin/services/{id}/delete', [new ServiceAdminController(), 'destroy']);

$router->get('/admin/blog', [new BlogAdminController(), 'index']);
$router->get('/admin/blog/create', [new BlogAdminController(), 'create']);
$router->post('/admin/blog', [new BlogAdminController(), 'store']);
$router->get('/admin/blog/{id}/edit', [new BlogAdminController(), 'edit']);
$router->post('/admin/blog/{id}', [new BlogAdminController(), 'update']);
$router->post('/admin/blog/{id}/delete', [new BlogAdminController(), 'destroy']);

$router->get('/admin/contacts', [new ContactAdminController(), 'index']);
$router->get('/admin/contacts/{id}', [new ContactAdminController(), 'show']);
$router->get('/admin/contacts/export', [new ContactAdminController(), 'export']);

$router->get('/admin/quotes', [new QuoteAdminController(), 'index']);
$router->get('/admin/quotes/{id}', [new QuoteAdminController(), 'show']);
$router->post('/admin/quotes/{id}/status', [new QuoteAdminController(), 'updateStatus']);
$router->get('/admin/quotes/export', [new QuoteAdminController(), 'export']);

$router->get('/admin/settings/email', [new EmailSettingsController(), 'edit']);
$router->post('/admin/settings/email', [new EmailSettingsController(), 'save']);
$router->post('/admin/settings/email/test', [new EmailSettingsController(), 'test']);

$router->get('/admin/media', [new MediaAdminController(), 'index']);
$router->post('/admin/media/upload', [new MediaAdminController(), 'upload']);
$router->post('/admin/media/{id}/delete', [new MediaAdminController(), 'destroy']);

return $router;
