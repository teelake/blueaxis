<?php
$h = $blocks['hero'] ?? [];
$a = $blocks['about'] ?? [];
$c = $blocks['cta'] ?? [];
$t = $blocks['testimonials'] ?? [];
$n = $blocks['newsletter'] ?? [];
$trustJson = htmlspecialchars(json_encode($trustItems), ENT_QUOTES, 'UTF-8');
$testimonialJson = htmlspecialchars(json_encode($testimonialItems), ENT_QUOTES, 'UTF-8');
?>
<form method="post" action="<?= url('admin/content/home') ?>" x-data="adminTabs('hero')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav" role="tablist">
      <button type="button" class="admin-tab-btn" :class="tab === 'hero' && 'active'" @click="tab = 'hero'">Hero banner</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'about' && 'active'" @click="tab = 'about'">About section</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'trust' && 'active'" @click="tab = 'trust'">Trust stats</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'testimonials' && 'active'" @click="tab = 'testimonials'">Testimonials</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'newsletter' && 'active'" @click="tab = 'newsletter'">Newsletter</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'cta' && 'active'" @click="tab = 'cta'">Call to action</button>
    </nav>

    <div class="admin-panel__body space-y-6">
      <div x-show="tab === 'hero'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Hero banner</h2>
          <p class="admin-section-desc">The first thing visitors see at the top of your homepage.</p>
        </div>
        <?php
        $heroFields = [
            'eyebrow' => ['Small label above headline', 'e.g. Canadian Logistics Partner'],
            'title' => ['Main headline', ''],
            'lead' => ['Intro paragraph', ''],
            'cta_primary_label' => ['Primary button text', 'Request a Quote'],
            'cta_primary_url' => ['Primary button link', '/quote'],
            'cta_secondary_label' => ['Secondary button text', 'Our Services'],
            'cta_secondary_url' => ['Secondary button link', '/services'],
        ];
        foreach ($heroFields as $key => [$label, $placeholder]):
        ?>
          <?php \App\Core\View::partial('admin/field', [
              'label' => $label,
              'name' => "hero[{$key}]",
              'value' => $h[$key]['content'] ?? '',
              'placeholder' => $placeholder,
              'type' => in_array($key, ['lead'], true) ? 'textarea' : 'text',
          ]); ?>
        <?php endforeach; ?>
      </div>

      <div x-show="tab === 'about'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">About section</h2>
          <p class="admin-section-desc">Short company overview on the homepage.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Section heading',
            'name' => 'about[title]',
            'value' => $a['title']['content'] ?? '',
        ]); ?>
        <div class="admin-field">
          <label class="admin-label">Section body</label>
          <p class="admin-hint">Use the toolbar to format text. No coding needed.</p>
          <?php \App\Core\View::partial('rich-editor', [
              'name' => 'about[body]',
              'id' => 'home_about_body',
              'value' => $a['body']['content'] ?? '',
              'height' => 240,
          ]); ?>
        </div>
      </div>

      <div x-show="tab === 'trust'" x-cloak x-data="adminRepeater(<?= $trustJson ?>, {stat:'',label:''})">
        <div>
          <h2 class="admin-section-title">Trust indicators</h2>
          <p class="admin-section-desc">Four short stats shown below the hero (e.g. “B2B” / “Wholesale focus”).</p>
        </div>
        <template x-for="(row, index) in rows" :key="index">
          <div class="admin-repeater-row sm:grid-cols-2 sm:grid">
            <div class="admin-field">
              <label class="admin-label" x-text="'Stat ' + (index + 1)"></label>
              <input type="text" class="admin-input" x-model="row.stat" :name="'trust_items[' + index + '][stat]'" placeholder="B2B" />
            </div>
            <div class="admin-field">
              <label class="admin-label">Description</label>
              <input type="text" class="admin-input" x-model="row.label" :name="'trust_items[' + index + '][label]'" placeholder="Wholesale focus" />
            </div>
            <button type="button" class="admin-btn-ghost sm:col-span-2" @click="remove(index)" x-show="rows.length > 1">Remove</button>
          </div>
        </template>
        <button type="button" class="admin-btn-add" @click="add()">+ Add another stat</button>
      </div>

      <div x-show="tab === 'testimonials'" x-cloak>
        <div class="max-w-2xl space-y-5 mb-8">
          <div>
            <h2 class="admin-section-title">Testimonials section</h2>
            <p class="admin-section-desc">Headings and partner quotes on the homepage.</p>
          </div>
          <?php \App\Core\View::partial('admin/field', ['label' => 'Small label', 'name' => 'testimonials[eyebrow]', 'value' => $t['eyebrow']['content'] ?? '', 'placeholder' => 'Testimonials']); ?>
          <?php \App\Core\View::partial('admin/field', ['label' => 'Section title', 'name' => 'testimonials[title]', 'value' => $t['title']['content'] ?? '']); ?>
          <?php \App\Core\View::partial('admin/field', ['label' => 'Short description', 'name' => 'testimonials[lead]', 'value' => $t['lead']['content'] ?? '', 'type' => 'textarea']); ?>
        </div>
        <div x-data="adminRepeater(<?= $testimonialJson ?>, {quote:'',name:'',role:'',company:''})" class="space-y-4">
          <p class="text-sm font-medium text-slate-700">Customer quotes</p>
          <template x-for="(row, index) in rows" :key="index">
            <div class="admin-repeater-row space-y-4">
              <div class="admin-field">
                <label class="admin-label">Quote</label>
                <textarea class="admin-textarea" rows="3" x-model="row.quote" :name="'testimonial_items[' + index + '][quote]'"></textarea>
              </div>
              <div class="grid sm:grid-cols-3 gap-4">
                <div class="admin-field">
                  <label class="admin-label">Name</label>
                  <input type="text" class="admin-input" x-model="row.name" :name="'testimonial_items[' + index + '][name]'" />
                </div>
                <div class="admin-field">
                  <label class="admin-label">Job title</label>
                  <input type="text" class="admin-input" x-model="row.role" :name="'testimonial_items[' + index + '][role]'" />
                </div>
                <div class="admin-field">
                  <label class="admin-label">Company</label>
                  <input type="text" class="admin-input" x-model="row.company" :name="'testimonial_items[' + index + '][company]'" />
                </div>
              </div>
              <button type="button" class="admin-btn-ghost" @click="remove(index)" x-show="rows.length > 1">Remove quote</button>
            </div>
          </template>
          <button type="button" class="admin-btn-add" @click="add()">+ Add another quote</button>
        </div>
      </div>

      <div x-show="tab === 'newsletter'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Newsletter signup</h2>
          <p class="admin-section-desc">Email capture section before the footer call-to-action.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Small label', 'name' => 'newsletter[eyebrow]', 'value' => $n['eyebrow']['content'] ?? '', 'placeholder' => 'Stay informed']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Heading', 'name' => 'newsletter[title]', 'value' => $n['title']['content'] ?? '']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Description', 'name' => 'newsletter[lead]', 'value' => $n['lead']['content'] ?? '', 'type' => 'textarea']); ?>
      </div>

      <div x-show="tab === 'cta'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Call to action</h2>
          <p class="admin-section-desc">Bottom banner encouraging visitors to get in touch.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Heading', 'name' => 'cta[title]', 'value' => $c['title']['content'] ?? '']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Description', 'name' => 'cta[body]', 'value' => $c['body']['content'] ?? '', 'type' => 'textarea']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Button text', 'name' => 'cta[button_label]', 'value' => $c['button_label']['content'] ?? '', 'placeholder' => 'Get in Touch']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Button link', 'name' => 'cta[button_url]', 'value' => $c['button_url']['content'] ?? '', 'placeholder' => '/contact', 'hint' => 'Use /quote or /contact — no full URL needed.']); ?>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <p class="text-sm text-slate-500">Changes apply to the live homepage after you save.</p>
      <button type="submit" class="btn-primary">Save home page</button>
    </div>
  </div>
</form>
