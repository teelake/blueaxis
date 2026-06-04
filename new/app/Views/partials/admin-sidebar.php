<?php
use App\Core\Auth;
use App\Core\Permission;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$links = [
    ['url' => 'admin/dashboard', 'label' => 'Dashboard', 'match' => '/admin/dashboard', 'icon' => 'grid', 'permission' => Permission::DASHBOARD],
    ['url' => 'admin/content/home', 'label' => 'Home page', 'match' => '/admin/content/home', 'icon' => 'home', 'permission' => Permission::CONTENT],
    ['url' => 'admin/content/about', 'label' => 'About page', 'match' => '/admin/content/about', 'icon' => 'info', 'permission' => Permission::CONTENT],
    ['url' => 'admin/services', 'label' => 'Services', 'match' => '/admin/services', 'icon' => 'box', 'permission' => Permission::SERVICES],
    ['url' => 'admin/products', 'label' => 'Products', 'match' => '/admin/products', 'icon' => 'package', 'permission' => Permission::PRODUCTS],
    ['url' => 'admin/blog', 'label' => 'Blog', 'match' => '/admin/blog', 'icon' => 'article', 'permission' => Permission::BLOG],
    ['url' => 'admin/quotes', 'label' => 'Quotes', 'match' => '/admin/quotes', 'icon' => 'quote', 'permission' => Permission::LEADS_QUOTES],
    ['url' => 'admin/contacts', 'label' => 'Contacts', 'match' => '/admin/contacts', 'icon' => 'mail', 'permission' => Permission::LEADS_CONTACTS],
    ['url' => 'admin/media', 'label' => 'Media library', 'match' => '/admin/media', 'icon' => 'image', 'permission' => Permission::MEDIA],
    ['url' => 'admin/users', 'label' => 'Team & roles', 'match' => '/admin/users', 'icon' => 'users', 'permission' => Permission::USERS_MANAGE],
    ['url' => 'admin/settings/email', 'label' => 'Email settings', 'match' => '/admin/settings/email', 'icon' => 'settings', 'permission' => Permission::SETTINGS_EMAIL],
];
$icons = [
    'grid' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>',
    'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'box' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
    'package' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 13V7l-8-4-8 4v6m16 0v6l-8 4m8-10L12 17m0 0L4 13m8 4V7M4 7v6l8 4"/>',
    'article' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>',
    'quote' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    'image' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
    'settings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
];
$user = Auth::user();
?>
<aside class="admin-sidebar flex flex-col">
  <div class="admin-sidebar__brand">
    <p class="font-semibold text-brand-navy tracking-tight">BlueAxis</p>
    <p class="text-xs text-slate-500 mt-0.5"><?= e($user['role_name'] ?? 'Admin') ?></p>
  </div>
  <nav class="admin-sidebar__nav" aria-label="Admin">
    <?php foreach ($links as $link): ?>
      <?php if (!Auth::can($link['permission'])) continue; ?>
      <?php $active = str_contains($path, $link['match']); ?>
      <a href="<?= url($link['url']) ?>" class="admin-nav-link <?= $active ? 'active' : '' ?>">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><?= $icons[$link['icon']] ?? '' ?></svg>
        <?= e($link['label']) ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <div class="p-3 border-t border-slate-100 mt-auto">
    <a href="<?= url('/') ?>" target="_blank" rel="noopener noreferrer" class="admin-nav-link">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
      View website
    </a>
  </div>
</aside>
