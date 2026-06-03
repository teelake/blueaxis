<?php
use App\Models\Setting;
$contact = Setting::allByGroup('contact');
?>
<footer class="bg-brand-navy-dark text-slate-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
      <div class="lg:col-span-2">
        <img src="<?= asset('images/BLUEAXIS_logo.png') ?>" alt="" class="h-14 w-auto mb-6 brightness-0 invert opacity-90" />
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
  </div>
</footer>
