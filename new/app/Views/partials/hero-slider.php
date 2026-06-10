<?php
/** @var array<int, array<string, mixed>> $slides */
if (empty($slides)) {
    return;
}
?>
<section class="hero-slider hero-section relative overflow-hidden bg-brand-navy-dark text-white" x-data="heroSlider(<?= count($slides) ?>)" x-init="start()">
  <div class="hero-slider__track">
    <?php foreach ($slides as $i => $slide): ?>
      <?php
      $slideType = ($slide['slide_type'] ?? '') === 'text' || empty($slide['image_path']) ? 'text' : 'image';
      ?>
      <div
        class="hero-slider__slide hero-slider__slide--<?= e($slideType) ?>"
        :class="{ 'is-active': index === <?= (int) $i ?> }"
        x-show="index === <?= (int) $i ?>"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
      >
        <?php if ($slideType === 'image'): ?>
          <img
            src="<?= e(media_url((string) $slide['image_path'])) ?>"
            alt="<?= e((string) ($slide['title'] ?? 'BlueAxis logistics')) ?>"
            class="hero-slider__image"
          />
          <div class="hero-slider__overlay"></div>
        <?php else: ?>
          <?php \App\Core\View::partial('hero-background'); ?>
        <?php endif; ?>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 lg:py-32">
          <?php \App\Core\View::partial('hero-slide-content', ['slide' => $slide]); ?>
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
