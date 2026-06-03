<?php
$h = $blocks['hero'] ?? [];
$a = $blocks['about'] ?? [];
$c = $blocks['cta'] ?? [];
?>
<form method="post" action="<?= url('admin/content/home') ?>" class="space-y-8 max-w-3xl">
  <?= \App\Core\Csrf::field() ?>
  <fieldset class="card space-y-4">
    <legend class="font-semibold text-brand-navy">Hero</legend>
    <?php foreach (['eyebrow','title','lead','cta_primary_label','cta_primary_url','cta_secondary_label','cta_secondary_url'] as $f): ?>
      <div><label class="text-sm font-medium"><?= e($f) ?></label>
        <input name="hero[<?= $f ?>]" value="<?= e($h[$f]['content'] ?? '') ?>" class="input-field mt-1" /></div>
    <?php endforeach; ?>
  </fieldset>
  <fieldset class="card space-y-4">
    <legend class="font-semibold text-brand-navy">About section</legend>
    <input name="about[title]" value="<?= e($a['title']['content'] ?? '') ?>" class="input-field" />
    <textarea name="about[body]" rows="6" class="input-field"><?= e($a['body']['content'] ?? '') ?></textarea>
  </fieldset>
  <fieldset class="card space-y-4">
    <legend class="font-semibold text-brand-navy">CTA</legend>
    <?php foreach (['title','body','button_label','button_url'] as $f): ?>
      <input name="cta[<?= $f ?>]" value="<?= e($c[$f]['content'] ?? '') ?>" class="input-field" placeholder="<?= $f ?>" />
    <?php endforeach; ?>
  </fieldset>
  <button type="submit" class="btn-primary">Save home page</button>
</form>
