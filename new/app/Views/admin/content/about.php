<?php
$valuesJson = htmlspecialchars(json_encode($valueItems), ENT_QUOTES, 'UTF-8');
?>
<form method="post" action="<?= url('admin/content/about') ?>" x-data="adminTabs('overview')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'overview' && 'active'" @click="tab = 'overview'">Overview</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'mission' && 'active'" @click="tab = 'mission'">Mission</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'vision' && 'active'" @click="tab = 'vision'">Vision</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'values' && 'active'" @click="tab = 'values'">Core values</button>
    </nav>

    <div class="admin-panel__body">
      <?php foreach (['overview' => 'Company overview', 'mission' => 'Mission', 'vision' => 'Vision'] as $sec => $heading): ?>
        <div x-show="tab === '<?= $sec ?>'" x-cloak class="space-y-5 max-w-2xl">
          <div>
            <h2 class="admin-section-title"><?= e($heading) ?></h2>
            <p class="admin-section-desc">Content for the <?= strtolower($heading) ?> block on the About page.</p>
          </div>
          <?php \App\Core\View::partial('admin/field', [
              'label' => 'Heading',
              'name' => "{$sec}[title]",
              'value' => $blocks[$sec]['title']['content'] ?? '',
          ]); ?>
          <div class="admin-field">
            <label class="admin-label">Body text</label>
            <?php \App\Core\View::partial('rich-editor', [
                'name' => $sec . '[body]',
                'id' => 'about_' . $sec . '_body',
                'value' => $blocks[$sec]['body']['content'] ?? '',
                'height' => 220,
            ]); ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div x-show="tab === 'values'" x-cloak x-data="adminRepeater(<?= $valuesJson ?>, {title:'',description:''})">
        <div class="mb-6">
          <h2 class="admin-section-title">Core values</h2>
          <p class="admin-section-desc">List the principles that guide your company (usually 3–5 items).</p>
        </div>
        <template x-for="(row, index) in rows" :key="index">
          <div class="admin-repeater-row space-y-4">
            <div class="admin-field">
              <label class="admin-label" x-text="'Value ' + (index + 1) + ' title'"></label>
              <input type="text" class="admin-input" x-model="row.title" :name="'value_items[' + index + '][title]'" placeholder="Integrity" />
            </div>
            <div class="admin-field">
              <label class="admin-label">Description</label>
              <textarea class="admin-textarea" rows="2" x-model="row.description" :name="'value_items[' + index + '][description]'" placeholder="What this means for partners…"></textarea>
            </div>
            <button type="button" class="admin-btn-ghost" @click="remove(index)" x-show="rows.length > 1">Remove value</button>
          </div>
        </template>
        <button type="button" class="admin-btn-add" @click="add()">+ Add another value</button>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <p class="text-sm text-slate-500">Changes apply to the About page after you save.</p>
      <button type="submit" class="btn-primary">Save about page</button>
    </div>
  </div>
</form>
