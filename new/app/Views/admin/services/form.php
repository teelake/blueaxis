<?php
$benefitsJson = htmlspecialchars(json_encode($benefitItems ?? [['text' => '']]), ENT_QUOTES, 'UTF-8');
?>
<form method="post" action="<?= $service ? url('admin/services/' . $service['id']) : url('admin/services') ?>" x-data="adminTabs('details')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'details' && 'active'" @click="tab = 'details'">Details</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'content' && 'active'" @click="tab = 'content'">Description</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'benefits' && 'active'" @click="tab = 'benefits'">Benefits</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'seo' && 'active'" @click="tab = 'seo'">SEO & visibility</button>
    </nav>

    <div class="admin-panel__body">
      <div x-show="tab === 'details'" x-cloak class="space-y-5 max-w-2xl">
        <?php \App\Core\View::partial('admin/field', ['label' => 'Service name', 'name' => 'title', 'value' => $service['title'] ?? '', 'required' => true]); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'URL slug', 'name' => 'slug', 'value' => $service['slug'] ?? '', 'hint' => 'Leave blank to auto-generate from the title.', 'placeholder' => 'importation-sourcing']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Short summary', 'name' => 'excerpt', 'value' => $service['excerpt'] ?? '', 'type' => 'textarea', 'hint' => 'Shown on cards and listings.']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Display order', 'name' => 'sort_order', 'value' => (string) ($service['sort_order'] ?? 0), 'type' => 'number', 'hint' => 'Lower numbers appear first.']); ?>
        <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
          <input type="checkbox" name="is_published" class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy" <?= ($service['is_published'] ?? 1) ? 'checked' : '' ?> />
          Visible on the website
        </label>
      </div>

      <div x-show="tab === 'content'" x-cloak class="max-w-3xl">
        <h2 class="admin-section-title mb-2">Full description</h2>
        <p class="admin-section-desc">Detailed information on the service page.</p>
        <?php \App\Core\View::partial('rich-editor', [
            'name' => 'description',
            'id' => 'service_description',
            'value' => $service['description'] ?? '',
            'height' => 320,
        ]); ?>
      </div>

      <div x-show="tab === 'benefits'" x-cloak x-data="adminRepeater(<?= $benefitsJson ?>, {text:''})">
        <h2 class="admin-section-title mb-2">Key benefits</h2>
        <p class="admin-section-desc">Bullet points that highlight why partners choose this service.</p>
        <template x-for="(row, index) in rows" :key="index">
          <div class="admin-repeater-row flex gap-3 items-start">
            <div class="admin-field flex-1">
              <label class="admin-label" x-text="'Benefit ' + (index + 1)"></label>
              <input type="text" class="admin-input" x-model="row.text" :name="'benefits[' + index + '][text]'" placeholder="e.g. Verified supplier networks" />
            </div>
            <button type="button" class="admin-btn-ghost mt-8" @click="remove(index)" x-show="rows.length > 1">Remove</button>
          </div>
        </template>
        <button type="button" class="admin-btn-add" @click="add()">+ Add benefit</button>
      </div>

      <div x-show="tab === 'seo'" x-cloak class="space-y-5 max-w-2xl">
        <?php \App\Core\View::partial('admin/image-upload', [
            'name' => 'banner_image',
            'id' => 'service_banner',
            'value' => $service['banner_image'] ?? '',
            'label' => 'Banner image',
            'hint' => 'Hero image on the service detail page.',
            'csrf' => \App\Core\Csrf::token(),
        ]); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO title', 'name' => 'meta_title', 'value' => $service['meta_title'] ?? '', 'hint' => 'Optional. Defaults to service name.']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO description', 'name' => 'meta_description', 'value' => $service['meta_description'] ?? '', 'type' => 'textarea']); ?>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <a href="<?= url('admin/services') ?>" class="text-sm font-medium text-slate-600 hover:text-brand-navy">← Back to services</a>
      <div class="flex gap-3">
        <?php if ($service): ?>
          <button formaction="<?= url('admin/services/' . $service['id'] . '/delete') ?>" formmethod="post" type="submit" class="btn-secondary" onclick="return confirm('Delete this service permanently?')">Delete</button>
        <?php endif; ?>
        <button type="submit" class="btn-primary">Save service</button>
      </div>
    </div>
  </div>
</form>
