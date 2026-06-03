<?php if (empty($testimonials)) return; ?>
<section class="py-20 lg:py-28 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16" data-aos="fade-up">
      <p class="section-eyebrow mb-3"><?= e($testimonialsTitle ?? 'Partner feedback') ?></p>
      <h2 class="section-title"><?= e($testimonialsHeading ?? 'Trusted by wholesale partners') ?></h2>
      <?php if (!empty($testimonialsLead)): ?>
        <p class="text-slate-600 mt-4 text-base md:text-lg"><?= e($testimonialsLead) ?></p>
      <?php endif; ?>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
      <?php foreach ($testimonials as $i => $item): ?>
        <blockquote class="card flex flex-col h-full" data-aos="fade-up" data-aos-delay="<?= min($i * 80, 240) ?>">
          <div class="flex gap-0.5 text-brand-gold mb-4" aria-label="5 out of 5 stars">
            <?php for ($s = 0; $s < 5; $s++): ?>
              <svg class="w-4 h-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <?php endfor; ?>
          </div>
          <p class="text-slate-700 leading-relaxed flex-1">&ldquo;<?= e($item['quote'] ?? '') ?>&rdquo;</p>
          <footer class="mt-6 pt-6 border-t border-slate-100">
            <cite class="not-italic font-semibold text-brand-navy block"><?= e($item['name'] ?? '') ?></cite>
            <span class="text-sm text-slate-500"><?= e($item['role'] ?? '') ?><?= !empty($item['company']) ? ' · ' . e($item['company']) : '' ?></span>
          </footer>
        </blockquote>
      <?php endforeach; ?>
    </div>
  </div>
</section>
