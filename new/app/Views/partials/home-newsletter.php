<section id="newsletter" class="py-20 lg:py-24 bg-brand-navy-dark text-white scroll-mt-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
      <p class="section-eyebrow text-brand-gold-light mb-3"><?= e($newsletterEyebrow ?? 'Stay informed') ?></p>
      <h2 class="text-2xl sm:text-3xl md:text-4xl font-semibold tracking-tight mb-4"><?= e($newsletterTitle ?? 'Logistics insights for your inbox') ?></h2>
      <p class="text-slate-300 text-base md:text-lg mb-8 max-w-xl mx-auto"><?= e($newsletterLead ?? 'Industry updates, supply chain perspectives, and company news for B2B partners.') ?></p>

      <?php if ($newsletterSuccess = flash('newsletter_success')): ?>
        <div class="mb-6 rounded-lg bg-emerald-500/15 border border-emerald-400/30 text-emerald-100 px-4 py-3 text-sm"><?= e($newsletterSuccess) ?></div>
      <?php endif; ?>
      <?php if ($newsletterError = flash('newsletter_error')): ?>
        <div class="mb-6 rounded-lg bg-red-500/15 border border-red-400/30 text-red-100 px-4 py-3 text-sm"><?= e($newsletterError) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= url('newsletter/subscribe') ?>" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto w-full">
        <?= \App\Core\Csrf::field() ?>
        <label class="sr-only" for="newsletter-email">Email address</label>
        <input
          type="email"
          id="newsletter-email"
          name="email"
          required
          placeholder="Business email address"
          class="input-field flex-1 min-w-0 text-slate-900"
          autocomplete="email"
        />
        <button type="submit" class="btn-accent shrink-0 w-full sm:w-auto justify-center">Subscribe</button>
      </form>
      <p class="text-xs text-slate-500 mt-4">B2B updates only. Unsubscribe anytime.</p>
    </div>
  </div>
</section>
