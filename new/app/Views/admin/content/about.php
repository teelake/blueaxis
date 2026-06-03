<?php
$vals = $blocks['values']['content']['content'] ?? '[]';
?>
<form method="post" action="<?= url('admin/content/about') ?>" class="space-y-8 max-w-3xl">
  <?= \App\Core\Csrf::field() ?>
  <?php foreach (['overview', 'mission', 'vision'] as $sec): ?>
    <fieldset class="card space-y-3">
      <legend class="font-semibold capitalize"><?= e($sec) ?></legend>
      <input name="<?= $sec ?>[title]" value="<?= e($blocks[$sec]['title']['content'] ?? '') ?>" class="input-field" />
      <label class="block text-sm font-medium text-slate-700 mb-1">Body</label>
      <?php \App\Core\View::partial('rich-editor', [
          'name' => $sec . '[body]',
          'id' => 'about_' . $sec . '_body',
          'value' => $blocks[$sec]['body']['content'] ?? '',
          'height' => 220,
      ]); ?>
    </fieldset>
  <?php endforeach; ?>
  <fieldset class="card">
    <legend class="font-semibold">Values (JSON)</legend>
    <textarea name="values_json" rows="10" class="input-field font-mono text-xs"><?= e($vals) ?></textarea>
  </fieldset>
  <button type="submit" class="btn-primary">Save about page</button>
</form>
