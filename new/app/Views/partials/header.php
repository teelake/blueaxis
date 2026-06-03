<?php
$current = request_path();
$nav = [
    '/' => 'Home',
    '/about' => 'About',
    '/services' => 'Services',
    '/blog' => 'Blog',
    '/contact' => 'Contact',
];
?>
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100" x-data="{ open: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">
      <a href="<?= url('/') ?>" class="flex items-center gap-3 shrink-0">
        <img src="<?= asset('images/BLUEAXIS_logo.png') ?>" alt="BlueAxis Logistics & Warehousing" class="h-12 w-auto" width="160" height="48" />
      </a>
      <nav class="hidden lg:flex items-center gap-8" aria-label="Main">
        <?php foreach ($nav as $path => $label): ?>
          <a href="<?= url(ltrim($path, '/')) ?>"
             class="text-sm font-medium transition <?= ($current === $path || ($path !== '/' && str_starts_with($current, $path))) ? 'text-brand-navy' : 'text-slate-600 hover:text-brand-navy' ?>">
            <?= e($label) ?>
          </a>
        <?php endforeach; ?>
      </nav>
      <div class="hidden lg:flex items-center gap-4">
        <a href="<?= url('contact#quote') ?>" class="btn-primary">Request a Quote</a>
      </div>
      <button type="button" class="lg:hidden p-2 text-brand-navy" @click="open = !open" aria-label="Toggle menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
    <div class="lg:hidden pb-4" x-show="open" x-cloak>
      <?php foreach ($nav as $path => $label): ?>
        <a href="<?= url(ltrim($path, '/')) ?>" class="block py-2 text-sm font-medium text-slate-700"><?= e($label) ?></a>
      <?php endforeach; ?>
      <a href="<?= url('contact#quote') ?>" class="btn-primary mt-3 w-full">Request a Quote</a>
    </div>
  </div>
</header>
