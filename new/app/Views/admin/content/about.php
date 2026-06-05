<?php
$l = $blocks['leadership'] ?? [];
$valuesJson = htmlspecialchars(json_encode($valueItems), ENT_QUOTES, 'UTF-8');
$leadershipJson = htmlspecialchars(json_encode($leadershipMembers ?? []), ENT_QUOTES, 'UTF-8');
$csrf = \App\Core\Csrf::token();
$uploadUrl = url('admin/media/upload');
?>
<form method="post" action="<?= url('admin/content/about') ?>" x-data="adminTabs('overview')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'overview' && 'active'" @click="tab = 'overview'">Overview</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'mission' && 'active'" @click="tab = 'mission'">Mission</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'vision' && 'active'" @click="tab = 'vision'">Vision</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'values' && 'active'" @click="tab = 'values'">Core values</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'leadership' && 'active'" @click="tab = 'leadership'">Leadership</button>
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
          <?php \App\Core\View::partial('admin/image-upload', [
              'name' => $sec . '[image]',
              'id' => 'about_' . $sec . '_image',
              'value' => $blocks[$sec]['image']['content'] ?? '',
              'label' => 'Section image',
              'hint' => 'Optional image alongside this block on the About page.',
              'csrf' => $csrf,
          ]); ?>
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

      <div x-show="tab === 'leadership'" x-cloak x-data="adminRepeater(<?= $leadershipJson ?>, {name:'',role:'',bio:'',image_path:''})" class="space-y-4">
        <div class="mb-6 max-w-2xl space-y-5">
          <div>
            <h2 class="admin-section-title">Leadership section</h2>
            <p class="admin-section-desc">Team profiles shown at the bottom of the About page. Add a photo, name, and title for each leader.</p>
          </div>
          <?php \App\Core\View::partial('admin/field', [
              'label' => 'Section heading',
              'name' => 'leadership[title]',
              'value' => $l['title']['content'] ?? 'Leadership',
              'placeholder' => 'Leadership',
          ]); ?>
          <?php \App\Core\View::partial('admin/field', [
              'label' => 'Intro text',
              'name' => 'leadership[lead]',
              'value' => $l['lead']['content'] ?? '',
              'type' => 'textarea',
              'placeholder' => 'Short paragraph above the team grid (optional).',
          ]); ?>
        </div>
        <p class="text-sm font-medium text-slate-700">Team members</p>
        <template x-for="(row, index) in rows" :key="index">
          <div class="admin-repeater-row space-y-4 border border-slate-200 rounded-xl p-5 bg-slate-50/50">
            <p class="text-sm font-semibold text-slate-700" x-text="'Profile ' + (index + 1)"></p>
            <div class="image-upload-zone image-upload-zone--compact max-w-[140px]" data-image-upload data-upload-url="<?= e($uploadUrl) ?>" data-csrf="<?= e($csrf) ?>" :data-initial-path="row.image_path || ''">
              <div class="image-upload-zone__drop">
                <img data-upload-preview alt="" class="hidden" />
                <div data-upload-placeholder class="image-upload-zone__placeholder">
                  <p class="text-xs font-medium text-slate-600">Photo</p>
                </div>
                <input type="file" data-upload-input accept="image/*" class="sr-only" />
                <input type="hidden" :name="'leadership_items[' + index + '][image_path]'" data-upload-path x-model="row.image_path" />
              </div>
              <button type="button" data-upload-clear class="text-xs font-medium text-slate-500 hover:text-red-600 mt-2">Remove photo</button>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="admin-field">
                <label class="admin-label">Full name *</label>
                <input type="text" class="admin-input" x-model="row.name" :name="'leadership_items[' + index + '][name]'" placeholder="Jane Doe" />
              </div>
              <div class="admin-field">
                <label class="admin-label">Title / position</label>
                <input type="text" class="admin-input" x-model="row.role" :name="'leadership_items[' + index + '][role]'" placeholder="Chief Executive Officer" />
              </div>
            </div>
            <div class="admin-field">
              <label class="admin-label">Short bio (optional)</label>
              <textarea class="admin-textarea" rows="2" x-model="row.bio" :name="'leadership_items[' + index + '][bio]'" placeholder="One or two sentences…"></textarea>
            </div>
            <button type="button" class="admin-btn-ghost" @click="remove(index)" x-show="rows.length > 1">Remove profile</button>
          </div>
        </template>
        <button type="button" class="admin-btn-add" @click="add()">+ Add team member</button>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <p class="text-sm text-slate-500">Changes apply to the About page after you save.</p>
      <button type="submit" class="btn-primary" data-loading-text="Saving…">Save about page</button>
    </div>
  </div>
</form>
