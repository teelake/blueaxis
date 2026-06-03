<?php
use App\Core\Auth;
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$links = [
    ['url' => 'admin/dashboard', 'label' => 'Dashboard', 'match' => '/admin/dashboard'],
    ['url' => 'admin/content/home', 'label' => 'Home Page', 'match' => '/admin/content/home'],
    ['url' => 'admin/content/about', 'label' => 'About Page', 'match' => '/admin/content/about'],
    ['url' => 'admin/services', 'label' => 'Services', 'match' => '/admin/services'],
    ['url' => 'admin/blog', 'label' => 'Blog', 'match' => '/admin/blog'],
    ['url' => 'admin/quotes', 'label' => 'Quote Requests', 'match' => '/admin/quotes'],
    ['url' => 'admin/contacts', 'label' => 'Contacts', 'match' => '/admin/contacts'],
    ['url' => 'admin/media', 'label' => 'Media', 'match' => '/admin/media'],
];
if (Auth::hasRole('super_admin')) {
    $links[] = ['url' => 'admin/settings/email', 'label' => 'Email Settings', 'match' => '/admin/settings/email'];
}
?>
<aside class="hidden lg:flex w-64 flex-col bg-brand-navy text-white shrink-0">
  <div class="p-6 border-b border-white/10">
    <p class="font-semibold text-sm">BlueAxis CMS</p>
    <p class="text-xs text-slate-400 mt-1"><?= e(Auth::user()['role'] ?? '') ?></p>
  </div>
  <nav class="flex-1 p-4 space-y-1">
    <?php foreach ($links as $link): ?>
      <a href="<?= url($link['url']) ?>"
         class="admin-sidebar-link <?= str_contains($path, $link['match']) ? 'active' : '' ?>">
        <?= e($link['label']) ?>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
