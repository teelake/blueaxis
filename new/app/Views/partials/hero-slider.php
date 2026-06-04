<?php
/** @var array<int, array> $slides */
if (empty($slides)) {
    return;
}
?>
<section class="hero-slider relative overflow-hidden bg-brand-navy-dark text-white" x-data="heroSlider(<?= count($slides) ?>)" x-init="start()">
  <div class="hero-slider__track">
    <?php foreach ($slides as $i => $slide): ?>
      <div
        class="hero-slider__slide"
        :class="{ 'is-active': index === <?= (int) $i ?> }"
        x-show="index === <?= (int) $i ?>"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
      >
        <img src="<?= e(media_url($slide['image_path'])) ?>" alt="<?= e($slide['title'] ?? 'BlueAxis logistics') ?>" class="hero-slider__image" />
        <div class="hero-slider__overlay"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36">
          <div class="max-w-3xl">
            <?php if (!empty($slide['title'])): ?>
              <h2 class="font-sans font-semibold text-3xl sm:text-4xl lg:text-5xl leading-tight tracking-tight mb-4"><?= e($slide['title']) ?></h2>
            <?php endif; ?>
            <?php if (!empty($slide['subtitle'])): ?>
              <p class="text-lg text-slate-200 leading-relaxed mb-8 max-w-2xl"><?= e($slide['subtitle']) ?></p>
            <?php endif; ?>
            <?php if (!empty($slide['link_url'])): ?>
              <a href="<?= url(ltrim((string) $slide['link_url'], '/')) ?>" class="btn-accent"><?= e($slide['link_label'] ?? 'Learn more') ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if (count($slides) > 1): ?>
    <div class="hero-slider__dots" aria-label="Slide navigation">
      <?php foreach ($slides as $i => $slide): ?>
        <button type="button" class="hero-slider__dot" :class="{ 'is-active': index === <?= (int) $i ?> }" @click="go(<?= (int) $i ?>)" aria-label="Slide <?= $i + 1 ?>"></button>
      <?php endforeach; ?>
    </div>
    <button type="button" class="hero-slider__nav hero-slider__nav--prev" @click="prev()" aria-label="Previous slide">‹</button>
    <button type="button" class="hero-slider__nav hero-slider__nav--next" @click="next()" aria-label="Next slide">›</button>
  <?php endif; ?>
</section>
<script>
function heroSlider(total) {
  return {
    index: 0,
    total: total,
    timer: null,
    start() {
      if (this.total < 2) return;
      this.timer = setInterval(() => this.next(), 6000);
    },
    go(i) { this.index = i; },
    next() { this.index = (this.index + 1) % this.total; },
    prev() { this.index = (this.index - 1 + this.total) % this.total; },
  };
}
</script>
