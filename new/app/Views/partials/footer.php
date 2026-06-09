<?php
use App\Models\ContentBlock;
use App\Models\Setting;

$blocks = ContentBlock::forPage('footer');
$contact = Setting::allByGroup('contact');

$blurb = section($blocks['brand'] ?? [], 'blurb', 'BlueAxis Logistics & Warehousing Ltd. — importation, warehousing, and distribution of African food products for B2B partners across Canada.');
$navTitle = section($blocks['company_nav'] ?? [], 'title', 'Company');
$navLinks = content_json_list($blocks, 'company_nav', 'links', [
    ['label' => 'About', 'url' => '/about'],
    ['label' => 'Services', 'url' => '/services'],
    ['label' => 'Blog', 'url' => '/blog'],
    ['label' => 'Request a Quote', 'url' => '/quote'],
    ['label' => 'Contact', 'url' => '/contact'],
]);
$contactTitle = section($blocks['contact_col'] ?? [], 'title', 'Contact');
$copyright = section($blocks['bar'] ?? [], 'copyright', 'BlueAxis Logistics & Warehousing Ltd. All rights reserved.');
$tagline = section($blocks['bar'] ?? [], 'tagline', 'Manitoba · Canada-wide distribution');
$showCredit = ($blocks['credit']['show']['content'] ?? '1') !== '0';
?>
<footer class="bg-brand-navy-dark text-slate-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
      <div class="lg:col-span-2">
        <div class="mb-6">
          <?php \App\Core\View::partial('site-logo', ['variant' => 'footer']); ?>
        </div>
        <?php if ($blurb !== ''): ?>
          <p class="text-sm leading-relaxed max-w-md text-slate-400"><?= e($blurb) ?></p>
        <?php endif; ?>
        <div class="mt-6">
          <?php \App\Core\View::partial('social-links', ['variant' => 'footer']); ?>
        </div>
      </div>
      <?php if ($navLinks !== []): ?>
        <div>
          <h3 class="text-sm font-semibold text-white mb-4"><?= e($navTitle) ?></h3>
          <ul class="space-y-2 text-sm">
            <?php foreach ($navLinks as $link): ?>
              <?php
              $href = footer_nav_href((string) ($link['url'] ?? ''));
              $label = (string) ($link['label'] ?? '');
              if ($label === '') {
                  continue;
              }
              ?>
              <li><a href="<?= e($href) ?>" class="hover:text-brand-gold transition"><?= e($label) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
      <div>
        <h3 class="text-sm font-semibold text-white mb-4"><?= e($contactTitle) ?></h3>
        <ul class="space-y-2 text-sm">
          <?php if (!empty($contact['company_address'])): ?>
            <li><?= e($contact['company_address']) ?></li>
          <?php endif; ?>
          <?php if (!empty($contact['company_email'])): ?>
            <li><a href="mailto:<?= e($contact['company_email']) ?>" class="hover:text-brand-gold"><?= e($contact['company_email']) ?></a></li>
          <?php endif; ?>
          <?php if (!empty($contact['company_phone'])): ?>
            <li><?= e($contact['company_phone']) ?></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between gap-4 text-xs text-slate-500">
      <?php if ($copyright !== ''): ?>
        <p>&copy; <?= date('Y') ?> <?= e($copyright) ?></p>
      <?php endif; ?>
      <?php if ($tagline !== ''): ?>
        <p><?= e($tagline) ?></p>
      <?php endif; ?>
    </div>
    <?php if ($showCredit): ?>
      <p class="mt-6 text-center text-xs text-slate-500">
        <span class="inline-flex items-center justify-center gap-1.5 flex-wrap">
          <span>Website made with</span>
          <svg class="w-3.5 h-3.5 text-brand-gold shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span>by</span>
          <a href="https://www.webspace.ng" class="font-medium text-slate-400 hover:text-brand-gold transition" target="_blank" rel="noopener noreferrer">Webspace</a>
        </span>
      </p>
    <?php endif; ?>
  </div>
</footer>
