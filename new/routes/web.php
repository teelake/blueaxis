<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\BlogController;
use App\Controllers\ContactController;
use App\Controllers\QuoteController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\NewsletterController;
use App\Controllers\SeoController;
use App\Controllers\ServiceController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\BlogAdminController;
use App\Controllers\Admin\ProfileAdminController;
use App\Controllers\BlogCommentController;
use App\Controllers\Admin\ContactAdminController;
use App\Controllers\Admin\ContentAdminController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\EmailSettingsController;
use App\Controllers\Admin\SiteSettingsController;
use App\Controllers\Admin\SocialSettingsController;
use App\Controllers\Admin\MediaAdminController;
use App\Controllers\Admin\ProductAdminController;
use App\Controllers\Admin\QuoteAdminController;
use App\Controllers\Admin\ServiceAdminController;
use App\Controllers\Admin\UserAdminController;
use App\Core\Auth;
use App\Core\Router;

$router = new Router();

// Public routes
$router->get('/', [new HomeController(), 'index']);
$router->post('/newsletter/subscribe', [new NewsletterController(), 'subscribe']);
$router->get('/about', [new AboutController(), 'index']);
$router->get('/services', [new ServiceController(), 'index']);
$router->get('/services/{slug}', [new ServiceController(), 'show']);
$router->get('/products', [new ProductController(), 'index']);
$router->get('/products/{slug}', [new ProductController(), 'show']);
$router->get('/blog', [new BlogController(), 'index']);
$router->get('/blog/{slug}', [new BlogController(), 'show']);
$router->post('/blog/{slug}/comments', [new BlogCommentController(), 'store']);
$router->get('/quote', [new QuoteController(), 'index']);
$router->post('/quote', [new QuoteController(), 'submit']);
$router->post('/quote/cart/add', [new QuoteController(), 'addToCart']);
$router->post('/quote/cart/remove', [new QuoteController(), 'removeFromCart']);
$router->get('/contact', [new ContactController(), 'index']);
$router->post('/contact', [new ContactController(), 'submitContact']);
$router->get('/sitemap.xml', [new SeoController(), 'sitemap']);
$router->get('/robots.txt', [new SeoController(), 'robots']);

// Admin auth
$router->get('/admin/login', [new AuthController(), 'loginForm']);
$router->post('/admin/login', [new AuthController(), 'login']);
$router->post('/admin/logout', [new AuthController(), 'logout']);

$router->get('/admin/profile', [new ProfileAdminController(), 'edit']);
$router->post('/admin/profile', [new ProfileAdminController(), 'update']);
$router->get('/admin/profile/password', [new ProfileAdminController(), 'passwordForm']);
$router->post('/admin/profile/password', [new ProfileAdminController(), 'updatePassword']);

// Admin (protected via controller)
$router->get('/admin', [new DashboardController(), 'index']);
$router->get('/admin/dashboard', [new DashboardController(), 'index']);

$router->get('/admin/content/home', [new ContentAdminController(), 'editHome']);
$router->post('/admin/content/home', [new ContentAdminController(), 'saveHome']);
$router->get('/admin/content/about', [new ContentAdminController(), 'editAbout']);
$router->post('/admin/content/about', [new ContentAdminController(), 'saveAbout']);

$router->get('/admin/products', [new ProductAdminController(), 'index']);
$router->get('/admin/products/create', [new ProductAdminController(), 'create']);
$router->post('/admin/products', [new ProductAdminController(), 'store']);
$router->get('/admin/products/{id}/edit', [new ProductAdminController(), 'edit']);
$router->post('/admin/products/{id}', [new ProductAdminController(), 'update']);
$router->post('/admin/products/{id}/toggle-publish', [new ProductAdminController(), 'togglePublish']);
$router->post('/admin/products/{id}/delete', [new ProductAdminController(), 'destroy']);

$router->get('/admin/services', [new ServiceAdminController(), 'index']);
$router->get('/admin/services/create', [new ServiceAdminController(), 'create']);
$router->post('/admin/services', [new ServiceAdminController(), 'store']);
$router->get('/admin/services/{id}/edit', [new ServiceAdminController(), 'edit']);
$router->post('/admin/services/{id}', [new ServiceAdminController(), 'update']);
$router->post('/admin/services/{id}/toggle-publish', [new ServiceAdminController(), 'togglePublish']);
$router->post('/admin/services/{id}/delete', [new ServiceAdminController(), 'destroy']);

$router->get('/admin/blog', [new BlogAdminController(), 'index']);
$router->get('/admin/blog/create', [new BlogAdminController(), 'create']);
$router->post('/admin/blog', [new BlogAdminController(), 'store']);
$router->get('/admin/blog/{id}/edit', [new BlogAdminController(), 'edit']);
$router->post('/admin/blog/{id}', [new BlogAdminController(), 'update']);
$router->post('/admin/blog/{id}/toggle-status', [new BlogAdminController(), 'toggleStatus']);
$router->post('/admin/blog/{id}/delete', [new BlogAdminController(), 'destroy']);
$router->post('/admin/blog/{id}/comments/{commentId}/approve', [new BlogAdminController(), 'approveComment']);
$router->post('/admin/blog/{id}/comments/{commentId}/spam', [new BlogAdminController(), 'spamComment']);
$router->post('/admin/blog/{id}/comments/{commentId}/delete', [new BlogAdminController(), 'deleteComment']);

$router->get('/admin/contacts', [new ContactAdminController(), 'index']);
$router->get('/admin/contacts/{id}', [new ContactAdminController(), 'show']);
$router->get('/admin/contacts/export', [new ContactAdminController(), 'export']);

$router->get('/admin/quotes', [new QuoteAdminController(), 'index']);
$router->get('/admin/quotes/{id}', [new QuoteAdminController(), 'show']);
$router->post('/admin/quotes/{id}/status', [new QuoteAdminController(), 'updateStatus']);
$router->get('/admin/quotes/export', [new QuoteAdminController(), 'export']);

$router->get('/admin/users', [new UserAdminController(), 'index']);
$router->get('/admin/users/create', [new UserAdminController(), 'create']);
$router->post('/admin/users', [new UserAdminController(), 'store']);
$router->get('/admin/users/{id}/edit', [new UserAdminController(), 'edit']);
$router->post('/admin/users/{id}', [new UserAdminController(), 'update']);
$router->post('/admin/users/{id}/toggle-active', [new UserAdminController(), 'toggleActive']);

$router->get('/admin/settings/site', [new SiteSettingsController(), 'edit']);
$router->post('/admin/settings/site', [new SiteSettingsController(), 'save']);

$router->get('/admin/settings/social', [new SocialSettingsController(), 'edit']);
$router->post('/admin/settings/social', [new SocialSettingsController(), 'save']);

$router->get('/admin/settings/email', [new EmailSettingsController(), 'edit']);
$router->post('/admin/settings/email', [new EmailSettingsController(), 'save']);
$router->post('/admin/settings/email/test', [new EmailSettingsController(), 'test']);

$router->get('/admin/media', [new MediaAdminController(), 'index']);
$router->post('/admin/media/upload', [new MediaAdminController(), 'upload']);
$router->get('/admin/media/list', [new MediaAdminController(), 'listJson']);
$router->post('/admin/media/{id}/delete', [new MediaAdminController(), 'destroy']);

return $router;
