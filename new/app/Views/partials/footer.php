<?php
use App\Models\Setting;
$contact = Setting::allByGroup('contact');
?>
<footer class="bg-brand-navy-dark text-slate-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
      <div class="lg:col-span-2">
        <div class="mb-6">
          <?php \App\Core\View::partial('site-logo', ['variant' => 'footer']); ?>
        </div>
        <p class="text-sm leading-relaxed max-w-md text-slate-400">
          BlueAxis Logistics & Warehousing Ltd. — importation, warehousing, and distribution of African food products for B2B partners across Canada.
        </p>
      </div>
      <div>
        <h3 class="text-sm font-semibold text-white mb-4">Company</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="<?= url('about') ?>" class="hover:text-brand-gold transition">About</a></li>
          <li><a href="<?= url('services') ?>" class="hover:text-brand-gold transition">Services</a></li>
          <li><a href="<?= url('blog') ?>" class="hover:text-brand-gold transition">Blog</a></li>
          <li><a href="<?= url('quote') ?>" class="hover:text-brand-gold transition">Request a Quote</a></li>
          <li><a href="<?= url('contact') ?>" class="hover:text-brand-gold transition">Contact</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-sm font-semibold text-white mb-4">Contact</h3>
        <ul class="space-y-2 text-sm">
          <li><?= e($contact['company_address'] ?? 'Winnipeg, Manitoba, Canada') ?></li>
          <li><a href="mailto:<?= e($contact['company_email'] ?? 'info@blueaxis.com') ?>" class="hover:text-brand-gold"><?= e($contact['company_email'] ?? 'info@blueaxis.com') ?></a></li>
          <li><?= e($contact['company_phone'] ?? '') ?></li>
        </ul>
      </div>
    </div>
    <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between gap-4 text-xs text-slate-500">
      <p>&copy; <?= date('Y') ?> BlueAxis Logistics & Warehousing Ltd. All rights reserved.</p>
      <p>Manitoba · Canada-wide distribution</p>
    </div>
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
  </div>
</footer>
