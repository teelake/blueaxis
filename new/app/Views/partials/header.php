<?php
$current = request_path();
$quoteCount = quote_cart_count();
$nav = [
    '/' => 'Home',
    '/about' => 'About',
    '/services' => 'Services',
    '/products' => 'Products',
    '/blog' => 'Blog',
    '/contact' => 'Contact',
];
?>
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100" x-data="{ open: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between min-h-[5.5rem] sm:min-h-24 py-2 sm:py-2.5">
      <?php \App\Core\View::partial('site-logo', ['variant' => 'header']); ?>
      <nav class="hidden lg:flex items-center gap-8" aria-label="Main">
        <?php foreach ($nav as $path => $label): ?>
          <a href="<?= url(ltrim($path, '/')) ?>"
             class="nav-link <?= ($current === $path || ($path !== '/' && str_starts_with($current, $path))) ? 'text-brand-navy' : 'text-slate-600 hover:text-brand-navy' ?>">
            <?= e($label) ?>
          </a>
        <?php endforeach; ?>
      </nav>
      <div class="hidden lg:flex items-center gap-4">
        <a href="<?= url('quote') ?>" class="text-sm font-semibold text-brand-navy hover:text-brand-gold relative">
          Quote list<?php if ($quoteCount > 0): ?><span class="ml-1.5 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold text-white bg-brand-gold rounded-full"><?= $quoteCount ?></span><?php endif; ?>
        </a>
        <a href="<?= url('quote') ?>" class="btn-primary">Request a Quote</a>
      </div>
      <button type="button" class="lg:hidden p-2 text-brand-navy" @click="open = !open" aria-label="Toggle menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
    <div class="lg:hidden pb-4" x-show="open" x-cloak>
      <?php foreach ($nav as $path => $label): ?>
        <a href="<?= url(ltrim($path, '/')) ?>" class="block py-3 text-base font-medium text-slate-700"><?= e($label) ?></a>
      <?php endforeach; ?>
      <?php if ($quoteCount > 0): ?>
        <a href="<?= url('quote') ?>" class="block py-3 text-base font-medium text-brand-navy">Quote list (<?= $quoteCount ?>)</a>
      <?php endif; ?>
      <a href="<?= url('quote') ?>" class="btn-primary mt-3 w-full">Request a Quote</a>
    </div>
  </div>
</header>
