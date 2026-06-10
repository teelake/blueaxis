<?php
$h = $blocks['hero'] ?? [];
$a = $blocks['about'] ?? [];
$ind = $blocks['industries'] ?? [];
$c = $blocks['cta'] ?? [];
$t = $blocks['testimonials'] ?? [];
$n = $blocks['newsletter'] ?? [];
$csrf = \App\Core\Csrf::token();
$uploadUrl = url('admin/media/upload');
$trustJson = htmlspecialchars(json_encode($trustItems), ENT_QUOTES, 'UTF-8');
$testimonialJson = htmlspecialchars(json_encode($testimonialItems), ENT_QUOTES, 'UTF-8');
$slidesJson = htmlspecialchars(json_encode($heroSlides ?? []), ENT_QUOTES, 'UTF-8');
?>
<form method="post" action="<?= url('admin/content/home') ?>" x-data="adminTabs('slides')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav" role="tablist">
      <button type="button" class="admin-tab-btn" :class="tab === 'slides' && 'active'" @click="tab = 'slides'">Hero slider</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'hero' && 'active'" @click="tab = 'hero'">Hero text & CTAs</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'about' && 'active'" @click="tab = 'about'">About section</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'industries' && 'active'" @click="tab = 'industries'">Industries</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'trust' && 'active'" @click="tab = 'trust'">Trust stats</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'testimonials' && 'active'" @click="tab = 'testimonials'">Testimonials</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'newsletter' && 'active'" @click="tab = 'newsletter'">Newsletter</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'cta' && 'active'" @click="tab = 'cta'">Call to action</button>
    </nav>

    <div class="admin-panel__body space-y-6">
      <div x-show="tab === 'slides'" x-cloak x-data="adminRepeater(<?= $slidesJson ?>, {slide_type:'text',eyebrow:'',title:'',subtitle:'',image_path:'',cta_primary_label:'',cta_primary_url:'',cta_secondary_label:'',cta_secondary_url:'',is_active:1})" class="space-y-4">
        <div>
          <h2 class="admin-section-title">Hero slider</h2>
          <p class="admin-section-desc">Build a rotating homepage hero. Each slide can use a <strong>background image</strong> or the <strong>text-only</strong> pattern (dark blue with logistics graphics). Add eyebrow text, headline, description, and up to two buttons per slide.</p>
        </div>
        <template x-for="(row, index) in rows" :key="index">
          <div class="admin-repeater-row space-y-4 border border-slate-200 rounded-xl p-5 bg-slate-50/50">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <p class="text-sm font-semibold text-slate-700" x-text="'Slide ' + (index + 1)"></p>
              <div class="flex rounded-lg border border-slate-200 overflow-hidden text-sm">
                <button type="button" class="px-3 py-1.5 font-medium transition" :class="row.slide_type === 'text' ? 'bg-brand-navy text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" @click="row.slide_type = 'text'">Text only</button>
                <button type="button" class="px-3 py-1.5 font-medium transition border-l border-slate-200" :class="row.slide_type === 'image' ? 'bg-brand-navy text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" @click="row.slide_type = 'image'">Background image</button>
              </div>
            </div>
            <input type="hidden" :name="'hero_slides[' + index + '][slide_type]'" x-model="row.slide_type" />
            <div x-show="row.slide_type === 'image'" x-cloak>
              <div class="image-upload-zone" data-image-upload data-upload-url="<?= e($uploadUrl) ?>" data-csrf="<?= e($csrf) ?>" :data-initial-path="row.image_path || ''">
                <div class="image-upload-zone__drop">
                  <img data-upload-preview alt="" class="hidden" />
                  <div data-upload-placeholder class="image-upload-zone__placeholder">
                    <p class="text-sm font-medium text-slate-600">Drag & drop background image</p>
                  </div>
                  <input type="file" data-upload-input accept="image/*" class="sr-only" />
                  <input type="hidden" :name="'hero_slides[' + index + '][image_path]'" data-upload-path x-model="row.image_path" />
                </div>
                <button type="button" data-upload-clear class="text-xs font-medium text-slate-500 hover:text-red-600 mt-2">Remove image</button>
              </div>
            </div>
            <p x-show="row.slide_type === 'text'" x-cloak class="text-sm text-slate-500 rounded-lg border border-dashed border-slate-200 bg-white px-4 py-3">Uses the decorative navy pattern background — same look as your current text-only hero.</p>
            <div class="admin-field">
              <label class="admin-label">Eyebrow</label>
              <input type="text" class="admin-input" x-model="row.eyebrow" :name="'hero_slides[' + index + '][eyebrow]'" placeholder="Canadian Logistics Partner" />
            </div>
            <div class="admin-field">
              <label class="admin-label">Headline</label>
              <input type="text" class="admin-input" x-model="row.title" :name="'hero_slides[' + index + '][title]'" placeholder="Connecting African Food Supply to Canadian Markets" />
            </div>
            <div class="admin-field">
              <label class="admin-label">Description</label>
              <textarea class="admin-textarea" rows="3" x-model="row.subtitle" :name="'hero_slides[' + index + '][subtitle]'" placeholder="Short supporting paragraph…"></textarea>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="admin-field">
                <label class="admin-label">Primary button label</label>
                <input type="text" class="admin-input" x-model="row.cta_primary_label" :name="'hero_slides[' + index + '][cta_primary_label]'" placeholder="Request a Quote" />
              </div>
              <div class="admin-field">
                <label class="admin-label">Primary button link</label>
                <input type="text" class="admin-input" x-model="row.cta_primary_url" :name="'hero_slides[' + index + '][cta_primary_url]'" placeholder="/quote" />
              </div>
              <div class="admin-field">
                <label class="admin-label">Secondary button label</label>
                <input type="text" class="admin-input" x-model="row.cta_secondary_label" :name="'hero_slides[' + index + '][cta_secondary_label]'" placeholder="Our Services" />
              </div>
              <div class="admin-field">
                <label class="admin-label">Secondary button link</label>
                <input type="text" class="admin-input" x-model="row.cta_secondary_url" :name="'hero_slides[' + index + '][cta_secondary_url]'" placeholder="/services" />
              </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
              <input type="hidden" :name="'hero_slides[' + index + '][is_active]'" :value="row.is_active ? '1' : '0'" />
              <input type="checkbox" x-model="row.is_active" class="rounded border-slate-300" />
              Show this slide on the website
            </label>
            <button type="button" class="admin-btn-ghost" @click="remove(index)" x-show="rows.length > 1">Remove slide</button>
          </div>
        </template>
        <button type="button" class="admin-btn-add" @click="add()">+ Add slide</button>
      </div>

      <div x-show="tab === 'hero'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Hero fallback</h2>
          <p class="admin-section-desc">Used only when no slides are saved in the <strong>Hero slider</strong> tab. If you have active slides, edit content there instead.</p>
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
        <?php \App\Core\View::partial('admin/image-upload', [
            'name' => 'about[image]',
            'id' => 'home_about_image',
            'value' => $a['image']['content'] ?? '',
            'label' => 'Section image',
            'hint' => 'Optional photo shown beside the about text (replaces the checklist card when set).',
            'csrf' => $csrf,
        ]); ?>
      </div>

      <div x-show="tab === 'industries'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Industries section</h2>
          <p class="admin-section-desc">Dark band on the homepage. Upload an image to show beside the text; otherwise the industry list is shown.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Small label', 'name' => 'industries[eyebrow]', 'value' => $ind['eyebrow']['content'] ?? '', 'placeholder' => 'Industries Served']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Heading', 'name' => 'industries[title]', 'value' => $ind['title']['content'] ?? '']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Description', 'name' => 'industries[lead]', 'value' => $ind['lead']['content'] ?? '', 'type' => 'textarea']); ?>
        <?php \App\Core\View::partial('admin/image-upload', [
            'name' => 'industries[image]',
            'id' => 'home_industries_image',
            'value' => $ind['image']['content'] ?? '',
            'label' => 'Section image',
            'hint' => 'Warehouse, fleet, or partner operations photo.',
            'csrf' => $csrf,
        ]); ?>
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
        <?php \App\Core\View::partial('admin/image-upload', [
            'name' => 'cta[image]',
            'id' => 'home_cta_image',
            'value' => $c['image']['content'] ?? '',
            'label' => 'Banner image',
            'hint' => 'Optional image on the left side of the bottom call-to-action.',
            'csrf' => $csrf,
        ]); ?>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <p class="text-sm text-slate-500">Changes apply to the live homepage after you save.</p>
      <button type="submit" class="btn-primary" data-loading-text="Saving…">Save home page</button>
    </div>
  </div>
</form>
