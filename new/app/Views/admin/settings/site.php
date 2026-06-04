<?php $csrf = \App\Core\Csrf::token(); ?>
<form method="post" action="<?= url('admin/settings/site') ?>" class="admin-panel">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel__body space-y-8 max-w-2xl">
    <div>
      <h2 class="admin-section-title">Site logo</h2>
      <p class="admin-section-desc">This image is used in the header and footer on every public page. PNG with a transparent background works best.</p>
    </div>

    <?php \App\Core\View::partial('admin/image-upload', [
        'name' => 'site_logo_path',
        'id' => 'site_logo',
        'value' => $logoPath,
        'label' => 'Logo image',
        'hint' => 'Recommended: wide format, at least 280px wide. Drag and drop or click to upload.',
        'csrf' => $csrf,
    ]); ?>

    <?php \App\Core\View::partial('admin/field', [
        'label' => 'Alt text (accessibility)',
        'name' => 'site_logo_alt',
        'value' => $logoAlt,
        'hint' => 'Describes the logo for screen readers and SEO.',
        'placeholder' => 'BlueAxis Logistics & Warehousing',
    ]); ?>

    <label class="flex items-start gap-3 text-sm font-medium text-slate-700 max-w-lg">
      <input
        type="checkbox"
        name="site_logo_footer_invert"
        class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy mt-0.5"
        <?= $footerInvert ? 'checked' : '' ?>
      />
      <span>
        Lighten logo in footer
        <span class="block font-normal text-slate-500 mt-1">Turn on for full-color logos on the dark footer (makes them white). Turn off if you upload a logo that is already light-colored.</span>
      </span>
    </label>

    <hr class="border-slate-200" />

    <div>
      <h2 class="admin-section-title">Favicon</h2>
      <p class="admin-section-desc">Small icon shown in the browser tab and when saving the site to a phone home screen. Square PNG (32×32 or 512×512) or ICO works best.</p>
    </div>

    <?php \App\Core\View::partial('admin/image-upload', [
        'name' => 'site_favicon_path',
        'id' => 'site_favicon',
        'value' => $faviconPath,
        'label' => 'Favicon image',
        'hint' => 'If empty, the default site icon is used.',
        'accept' => 'image/png,image/jpeg,image/webp,image/gif,image/x-icon,.ico',
        'formats' => 'PNG, ICO, JPG, WebP',
        'compact' => true,
        'csrf' => $csrf,
    ]); ?>

    <div class="favicon-preview">
      <img src="<?= e(site_favicon_url()) ?>" alt="" width="32" height="32" />
      <div class="text-left">
        <p class="text-sm font-medium text-slate-700">Current favicon preview</p>
        <p class="text-xs text-slate-500">Save to update what visitors see in their browser tab.</p>
      </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 space-y-4">
      <p class="text-sm font-semibold text-slate-700">Preview</p>
      <div>
        <p class="text-xs font-medium text-slate-500 mb-2">Header (light background)</p>
        <div class="rounded-lg bg-white border border-slate-100 p-6 flex items-center min-h-[5rem]">
          <?php \App\Core\View::partial('site-logo', ['variant' => 'header', 'link' => false]); ?>
        </div>
      </div>
      <div>
        <p class="text-xs font-medium text-slate-500 mb-2">Footer (dark background)</p>
        <div class="rounded-lg bg-brand-navy-dark p-6 flex items-center min-h-[5rem]">
          <?php \App\Core\View::partial('site-logo', ['variant' => 'footer', 'link' => false]); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-sticky-footer">
    <p class="text-sm text-slate-500">Changes apply to the live website after you save.</p>
    <button type="submit" class="btn-primary" data-loading-text="Saving…">Save branding</button>
  </div>
</form>
