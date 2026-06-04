<section class="bg-brand-navy text-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="section-eyebrow text-brand-gold-light mb-3">About Us</p>
    <h1 class="text-4xl md:text-5xl font-semibold max-w-3xl">BlueAxis Logistics & Warehousing Ltd.</h1>
  </div>
</section>

<?php
$overviewImg = section($blocks['overview'] ?? [], 'image');
$missionImg = section($blocks['mission'] ?? [], 'image');
$visionImg = section($blocks['vision'] ?? [], 'image');
?>
<section class="py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
    <div class="prose prose-lg prose-slate max-w-none">
      <h2><?= e(section($blocks['overview'] ?? [], 'title', 'Company Overview')) ?></h2>
      <?= section($blocks['overview'] ?? [], 'body') ?>
    </div>
    <?php if ($overviewImg !== ''): ?>
      <?php \App\Core\View::partial('section-image', ['imagePath' => $overviewImg, 'alt' => section($blocks['overview'] ?? [], 'title'), 'align' => 'right']); ?>
    <?php endif; ?>
  </div>
</section>

<section class="py-16 lg:py-20 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-8 md:gap-12 lg:gap-16">
    <?php foreach (['mission' => $missionImg, 'vision' => $visionImg] as $sec => $img): ?>
      <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
        <div class="card <?= $sec === 'vision' && $img !== '' ? 'lg:order-last' : '' ?>">
          <h2 class="text-xl font-semibold text-brand-navy mb-4"><?= e(section($blocks[$sec] ?? [], 'title', ucfirst($sec))) ?></h2>
          <div class="prose prose-slate max-w-none"><?= section($blocks[$sec] ?? [], 'body') ?></div>
        </div>
        <?php if ($img !== ''): ?>
          <?php \App\Core\View::partial('section-image', ['imagePath' => $img, 'alt' => section($blocks[$sec] ?? [], 'title'), 'align' => $sec === 'vision' ? 'left' : 'right']); ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="py-20">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="section-title text-center mb-12">Our Values</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($values as $v): ?>
        <div class="card text-center">
          <h3 class="font-semibold text-brand-navy mb-2"><?= e($v['title'] ?? '') ?></h3>
          <p class="text-sm text-slate-600"><?= e($v['description'] ?? '') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
$leadershipTitle = section($blocks['leadership'] ?? [], 'title', 'Leadership');
$leadershipLead = section($blocks['leadership'] ?? [], 'lead', '');
$hasLeadership = !empty($leadershipMembers);
?>
<?php if ($hasLeadership || $leadershipLead !== ''): ?>
<section class="py-20 lg:py-24 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
      <h2 class="section-title mb-4"><?= e($leadershipTitle) ?></h2>
      <?php if ($leadershipLead !== ''): ?>
        <p class="text-slate-600 leading-relaxed"><?= e($leadershipLead) ?></p>
      <?php elseif (!$hasLeadership): ?>
        <p class="text-slate-600">For partnership inquiries, please <a href="<?= url('contact') ?>" class="text-brand-navy font-semibold hover:text-brand-gold">contact us</a>.</p>
      <?php endif; ?>
    </div>
    <?php if ($hasLeadership): ?>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
        <?php foreach ($leadershipMembers as $member): ?>
          <article class="card text-center flex flex-col items-center">
            <?php if (!empty($member['image_path'])): ?>
              <img
                src="<?= e(media_url($member['image_path'])) ?>"
                alt="<?= e($member['name'] ?? '') ?>"
                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mb-5"
                width="96"
                height="96"
                loading="lazy"
              />
            <?php else: ?>
              <div class="w-24 h-24 rounded-full bg-brand-navy/10 mb-5 flex items-center justify-center text-brand-navy/40" aria-hidden="true">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              </div>
            <?php endif; ?>
            <h3 class="font-semibold text-brand-navy text-lg"><?= e($member['name'] ?? '') ?></h3>
            <?php if (!empty($member['role'])): ?>
              <p class="text-sm text-brand-gold font-medium mt-1"><?= e($member['role']) ?></p>
            <?php endif; ?>
            <?php if (!empty($member['bio'])): ?>
              <p class="text-sm text-slate-600 mt-3 leading-relaxed"><?= e($member['bio']) ?></p>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>
