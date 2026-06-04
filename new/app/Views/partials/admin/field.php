<?php
/** @var string $label @var string $name @var string $type @var string $value @var string $hint @var string $placeholder @var bool $required @var int|null $maxlength @var int|null $minlength */
$type = $type ?? 'text';
$id = $id ?? preg_replace('/[^a-z0-9_\-]/', '_', $name);
$fieldKey = $fieldKey ?? preg_replace('/\[.*$/', '', $name);
$error = field_error($fieldKey);
$invalid = field_invalid_class($fieldKey);
$inputClass = ($type === 'textarea' ? 'admin-textarea' : 'admin-input') . $invalid;
$maxAttr = isset($maxlength) ? ' maxlength="' . (int) $maxlength . '"' : '';
$minAttr = isset($minlength) ? ' minlength="' . (int) $minlength . '"' : '';
$reqAttr = !empty($required) ? ' required' : '';
?>
<div class="admin-field">
  <label class="admin-label" for="<?= e($id) ?>"><?= e($label) ?><?= !empty($required) ? ' <span class="text-red-500">*</span>' : '' ?></label>
  <?php if (!empty($hint)): ?>
    <p class="admin-hint"><?= e($hint) ?></p>
  <?php endif; ?>
  <?php if ($type === 'textarea'): ?>
    <textarea id="<?= e($id) ?>" name="<?= e($name) ?>" class="<?= $inputClass ?>" placeholder="<?= e($placeholder ?? '') ?>"<?= $reqAttr ?><?= $maxAttr ?><?= $minAttr ?>><?= e($value ?? '') ?></textarea>
  <?php elseif ($type === 'select' && !empty($options)): ?>
    <select id="<?= e($id) ?>" name="<?= e($name) ?>" class="<?= $inputClass ?>"<?= $reqAttr ?>>
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
      class="<?= $inputClass ?>"
      placeholder="<?= e($placeholder ?? '') ?>"
      <?= $reqAttr ?><?= $maxAttr ?><?= $minAttr ?>
      <?php if ($type === 'email'): ?> autocomplete="email"<?php endif; ?>
    />
  <?php endif; ?>
  <?php if ($error): ?>
    <p class="text-xs text-red-600 mt-1" role="alert"><?= $error ?></p>
  <?php endif; ?>
</div>
