<?php
$b = $blocks['brand'] ?? [];
$nav = $blocks['company_nav'] ?? [];
$col = $blocks['contact_col'] ?? [];
$bar = $blocks['bar'] ?? [];
$oldContact = is_array($_SESSION['_old']['contact'] ?? null) ? $_SESSION['_old']['contact'] : [];
$navJson = htmlspecialchars(json_encode($navLinks ?? []), ENT_QUOTES, 'UTF-8');
?>
<form method="post" action="<?= url('admin/content/footer') ?>" x-data="adminTabs('brand')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'brand' && 'active'" @click="tab = 'brand'">Brand</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'links' && 'active'" @click="tab = 'links'">Navigation</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'contact' && 'active'" @click="tab = 'contact'">Contact</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'bottom' && 'active'" @click="tab = 'bottom'">Bottom bar</button>
    </nav>

    <div class="admin-panel__body">
      <div x-show="tab === 'brand'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Brand column</h2>
          <p class="admin-section-desc">Short description under the logo. Logo and social icons are managed under <a href="<?= url('admin/settings/site') ?>" class="text-brand-navy font-medium hover:underline">Site branding</a> and <a href="<?= url('admin/settings/social') ?>" class="text-brand-navy font-medium hover:underline">Social media</a>.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Company description',
            'name' => 'brand[blurb]',
            'type' => 'textarea',
            'value' => $b['blurb']['content'] ?? '',
            'placeholder' => 'Brief summary of your company for the footer.',
        ]); ?>
      </div>

      <div x-show="tab === 'links'" x-cloak x-data="adminRepeater(<?= $navJson ?>, {label:'',url:''})" class="space-y-4 max-w-2xl">
        <div class="mb-2">
          <h2 class="admin-section-title">Company links</h2>
          <p class="admin-section-desc">Links in the middle column of the footer. Use paths like <code class="text-xs bg-slate-100 px-1 rounded">/about</code> or full URLs.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Column heading',
            'name' => 'company_nav[title]',
            'value' => $nav['title']['content'] ?? 'Company',
            'placeholder' => 'Company',
        ]); ?>
        <template x-for="(row, index) in rows" :key="index">
          <div class="admin-repeater-row space-y-4 border border-slate-200 rounded-xl p-5 bg-slate-50/50">
            <p class="text-sm font-semibold text-slate-700" x-text="'Link ' + (index + 1)"></p>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="admin-field">
                <label class="admin-label">Label</label>
                <input type="text" class="admin-input" x-model="row.label" :name="'nav_links[' + index + '][label]'" placeholder="About" />
              </div>
              <div class="admin-field">
                <label class="admin-label">URL</label>
                <input type="text" class="admin-input" x-model="row.url" :name="'nav_links[' + index + '][url]'" placeholder="/about" />
              </div>
            </div>
            <button type="button" class="admin-btn-ghost" @click="remove(index)" x-show="rows.length > 1">Remove link</button>
          </div>
        </template>
        <button type="button" class="admin-btn-add" @click="add()">+ Add link</button>
      </div>

      <div x-show="tab === 'contact'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Contact column</h2>
          <p class="admin-section-desc">Shown in the footer and on the <a href="<?= url('contact') ?>" target="_blank" rel="noopener noreferrer" class="text-brand-navy font-medium hover:underline">contact page</a>.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Column heading',
            'name' => 'contact_col[title]',
            'value' => $col['title']['content'] ?? 'Contact',
            'placeholder' => 'Contact',
        ]); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Address',
            'name' => 'contact[company_address]',
            'fieldKey' => 'company_address',
            'value' => (string) ($oldContact['company_address'] ?? $contact['company_address'] ?? ''),
            'type' => 'textarea',
            'placeholder' => 'Winnipeg, Manitoba, Canada',
            'required' => true,
        ]); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Email',
            'name' => 'contact[company_email]',
            'fieldKey' => 'company_email',
            'type' => 'email',
            'value' => (string) ($oldContact['company_email'] ?? $contact['company_email'] ?? ''),
            'placeholder' => 'info@blueaxis.com',
            'required' => true,
        ]); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Phone',
            'name' => 'contact[company_phone]',
            'fieldKey' => 'company_phone',
            'type' => 'tel',
            'value' => (string) ($oldContact['company_phone'] ?? $contact['company_phone'] ?? ''),
            'placeholder' => '+1 (204) 000-0000',
        ]); ?>
      </div>

      <div x-show="tab === 'bottom'" x-cloak class="space-y-5 max-w-2xl">
        <div>
          <h2 class="admin-section-title">Bottom bar</h2>
          <p class="admin-section-desc">Copyright line and tagline at the bottom of the footer. The year updates automatically.</p>
        </div>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Copyright text',
            'name' => 'bar[copyright]',
            'value' => $bar['copyright']['content'] ?? 'BlueAxis Logistics & Warehousing Ltd. All rights reserved.',
            'placeholder' => 'Company name and rights statement',
        ]); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Tagline',
            'name' => 'bar[tagline]',
            'value' => $bar['tagline']['content'] ?? 'Manitoba · Canada-wide distribution',
            'placeholder' => 'Manitoba · Canada-wide distribution',
        ]); ?>
        <label class="flex items-start gap-3 text-sm text-slate-700 cursor-pointer">
          <input type="checkbox" name="credit[show]" value="1" class="mt-1 rounded border-slate-300" <?= !empty($showCredit) ? 'checked' : '' ?> />
          <span>
            Show developer credit
            <span class="block font-normal text-slate-500 mt-1">Displays the “Website made with ♥ by Webspace” line at the very bottom.</span>
          </span>
        </label>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <p class="text-sm text-slate-500">Changes apply to the footer on every public page after you save.</p>
      <button type="submit" class="btn-primary" data-loading-text="Saving…">Save footer</button>
    </div>
  </div>
</form>
