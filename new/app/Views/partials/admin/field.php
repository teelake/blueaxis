<?php
/** @var string $label @var string $name @var string $type @var string $value @var string $hint @var string $placeholder @var bool $required */
$type = $type ?? 'text';
$id = $id ?? preg_replace('/[^a-z0-9_]/', '_', $name);
?>
<div class="admin-field">
  <label class="admin-label" for="<?= e($id) ?>"><?= e($label) ?><?= !empty($required) ? ' <span class="text-red-500">*</span>' : '' ?></label>
  <?php if (!empty($hint)): ?>
    <p class="admin-hint"><?= e($hint) ?></p>
  <?php endif; ?>
  <?php if ($type === 'textarea'): ?>
    <textarea id="<?= e($id) ?>" name="<?= e($name) ?>" class="admin-textarea" placeholder="<?= e($placeholder ?? '') ?>" <?= !empty($required) ? 'required' : '' ?>><?= e($value ?? '') ?></textarea>
  <?php elseif ($type === 'select' && !empty($options)): ?>
    <select id="<?= e($id) ?>" name="<?= e($name) ?>" class="admin-select" <?= !empty($required) ? 'required' : '' ?>>
      <?php foreach ($options as $optVal => $optLabel): ?>
        <option value="<?= e((string) $optVal) ?>" <?= (string) ($value ?? '') === (string) $optVal ? 'selected' : '' ?>><?= e($optLabel) ?></option>
      <?php endforeach; ?>
    </select>
  <?php else: ?>
    <input
      id="<?= e($id) ?>"
      type="<?= e($type) ?>"
      name="<?= e($name) ?>"
      value="<?= e($value ?? '') ?>"
      class="admin-input"
      placeholder="<?= e($placeholder ?? '') ?>"
      <?= !empty($required) ? 'required' : '' ?>
    />
  <?php endif; ?>
</div>
