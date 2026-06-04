<?php
$csrf = \App\Core\Csrf::field();
?>
<form method="post" action="<?= url('admin/settings/social') ?>" class="admin-panel">
  <?= $csrf ?>
  <div class="admin-panel__body space-y-6 max-w-2xl">
    <div>
      <h2 class="admin-section-title">Social profiles</h2>
      <p class="admin-section-desc">
        Paste the full URL for each network you use. Leave a field empty to hide that icon on the website.
        Links open in a new tab with secure referrer settings.
      </p>
    </div>

    <?php foreach ($platforms as $key => $meta): ?>
      <?php \App\Core\View::partial('admin/field', [
          'label' => $meta['label'],
          'name' => $key,
          'type' => 'url',
          'value' => $values[$key] ?? '',
          'placeholder' => $meta['placeholder'],
      ]); ?>
    <?php endforeach; ?>

    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6">
      <p class="text-sm font-semibold text-slate-700 mb-3">Where links appear</p>
      <ul class="text-sm text-slate-600 space-y-2 list-disc list-inside">
        <li>Footer on every page (below the logo)</li>
        <li>Contact page sidebar</li>
      </ul>
    </div>
  </div>

  <div class="admin-sticky-footer">
    <p class="text-sm text-slate-500">Save, then check the footer on the public site.</p>
    <button type="submit" class="btn-primary" data-loading-text="Saving…">Save social links</button>
  </div>
</form>
