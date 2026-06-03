<section class="border-t border-slate-100 bg-slate-50" aria-labelledby="map-heading">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="grid lg:grid-cols-3 gap-8 lg:gap-10 items-start">
      <div class="lg:col-span-1">
        <p class="section-eyebrow mb-2">Location</p>
        <h2 id="map-heading" class="section-title text-2xl md:text-3xl mb-4">Find us</h2>
        <p class="text-slate-600 text-sm leading-relaxed mb-6"><?= e($contact['company_address'] ?? 'Winnipeg, Manitoba, Canada') ?></p>
        <ul class="text-sm space-y-3">
          <li>
            <a href="mailto:<?= e($contact['company_email'] ?? '') ?>" class="font-medium text-brand-navy hover:text-brand-gold"><?= e($contact['company_email'] ?? '') ?></a>
          </li>
          <?php if (!empty($contact['company_phone'])): ?>
            <li class="text-slate-600"><?= e($contact['company_phone']) ?></li>
          <?php endif; ?>
        </ul>
        <a
          href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode($contact['company_address'] ?? 'Winnipeg, Manitoba, Canada') ?>"
          target="_blank"
          rel="noopener noreferrer"
          class="inline-flex mt-6 text-sm font-semibold text-brand-navy hover:text-brand-gold"
        >
          Open in Google Maps →
        </a>
      </div>
      <div class="lg:col-span-2 w-full min-w-0">
        <div class="rounded-xl overflow-hidden border border-slate-200 shadow-card aspect-[4/3] sm:aspect-[16/10] lg:aspect-[16/9] max-h-[420px] lg:max-h-none">
          <iframe
            title="BlueAxis location map"
            src="<?= e($mapEmbedUrl) ?>"
            class="w-full h-full min-h-[280px] sm:min-h-[320px]"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</section>
