<?php
$vals = $blocks['values']['content']['content'] ?? '[]';
?>
<form method="post" action="<?= url('admin/content/about') ?>" class="space-y-8 max-w-3xl">
  <?= \App\Core\Csrf::field() ?>
  <?php foreach (['overview', 'mission', 'vision'] as $sec): ?>
    <fieldset class="card space-y-3">
      <legend class="font-semibold capitalize"><?= e($sec) ?></legend>
      <input name="<?= $sec ?>[title]" value="<?= e($blocks[$sec]['title']['content'] ?? '') ?>" class="input-field" />
      <textarea name="<?= $sec ?>[body]" rows="5" class="input-field"><?= e($blocks[$sec]['body']['content'] ?? '') ?></textarea>
    </fieldset>
  <?php endforeach; ?>
  <fieldset class="card">
    <legend class="font-semibold">Values (JSON)</legend>
    <textarea name="values_json" rows="10" class="input-field font-mono text-xs"><?= e($vals) ?></textarea>
  </fieldset>
  <button type="submit" class="btn-primary">Save about page</button>
</form>
