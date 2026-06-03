<?php if (empty($testimonials)) return; ?>
<section class="py-20 lg:py-28 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-14 lg:mb-16" data-aos="fade-up">
      <p class="section-eyebrow mb-4"><?= e($testimonialsTitle ?? 'Testimonials') ?></p>
      <h2 class="section-title mb-5"><?= e($testimonialsHeading ?? 'Trusted by wholesale partners') ?></h2>
      <p class="text-brand-navy/80 text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
        <?= e($testimonialsLead ?? 'BlueAxis ensures seamless Manitoba and Canada-wide simplified import, storage, and distribution.') ?>
      </p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
      <?php foreach ($testimonials as $i => $item): ?>
        <blockquote class="testimonial-card" data-aos="fade-up" data-aos-delay="<?= min($i * 80, 240) ?>">
          <div class="flex justify-center mb-6" aria-hidden="true">
            <svg class="w-11 h-11 text-brand-gold" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2l2.9 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l7.1-.99L12 2z"/>
            </svg>
          </div>
          <p class="text-sm md:text-[0.9375rem] text-brand-navy leading-relaxed text-center flex-1">
            &ldquo;<?= e($item['quote'] ?? '') ?>&rdquo;
          </p>
          <footer class="mt-8 text-center">
            <cite class="not-italic font-bold text-brand-navy block text-base"><?= e($item['name'] ?? '') ?></cite>
            <span class="text-sm text-brand-navy/70 mt-1 block">
              <?= e($item['role'] ?? '') ?><?php if (!empty($item['company'])): ?> &ndash; <?= e($item['company']) ?><?php endif; ?>
            </span>
          </footer>
        </blockquote>
      <?php endforeach; ?>
    </div>
  </div>
</section>
