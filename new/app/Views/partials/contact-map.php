<section class="contact-map" aria-labelledby="map-heading">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 lg:pt-16 pb-6 text-center">
    <p class="section-eyebrow mb-2">Location</p>
    <h2 id="map-heading" class="section-title text-2xl md:text-3xl mb-3">Find us</h2>
    <p class="text-slate-600 text-sm md:text-base max-w-xl mx-auto"><?= e($contact['company_address'] ?? 'Winnipeg, Manitoba, Canada') ?></p>
    <a
      href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode($contact['company_address'] ?? 'Winnipeg, Manitoba, Canada') ?>"
      target="_blank"
      rel="noopener noreferrer"
      class="inline-flex mt-4 text-sm font-semibold text-brand-navy hover:text-brand-gold"
    >
      Open in Google Maps →
    </a>
  </div>
  <div class="contact-map__embed w-full">
    <iframe
      title="BlueAxis location map"
      src="<?= e($mapEmbedUrl) ?>"
      class="contact-map__iframe"
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"
      allowfullscreen
    ></iframe>
  </div>
</section>
