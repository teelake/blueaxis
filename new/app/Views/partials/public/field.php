<?php
/** @var string $label @var string $name @var string $type @var string $value @var bool $required @var int|null $maxlength @var int|null $minlength @var string $placeholder */
$type = $type ?? 'text';
$id = $id ?? $name;
$fieldKey = $fieldKey ?? $name;
$error = field_error($fieldKey);
$invalid = field_invalid_class($fieldKey);
$classes = 'input-field w-full' . $invalid;
$maxAttr = isset($maxlength) ? ' maxlength="' . (int) $maxlength . '"' : '';
$minAttr = isset($minlength) ? ' minlength="' . (int) $minlength . '"' : '';
$reqAttr = !empty($required) ? ' required' : '';
?>
<div>
  <label class="block text-sm font-medium mb-1" for="<?= e($id) ?>"><?= e($label) ?><?= !empty($required) ? ' *' : '' ?></label>
  <?php if ($type === 'textarea'): ?>
    <textarea id="<?= e($id) ?>" name="<?= e($name) ?>" class="<?= $classes ?>" rows="<?= (int) ($rows ?? 5) ?>" placeholder="<?= e($placeholder ?? '') ?>"<?= $reqAttr ?><?= $maxAttr ?><?= $minAttr ?>><?= old($name, $value ?? '') ?></textarea>
  <?php else: ?>
    <input
      id="<?= e($id) ?>"
      type="<?= e($type) ?>"
      name="<?= e($name) ?>"
      class="<?= $classes ?>"
      value="<?= old($name, $value ?? '') ?>"
      placeholder="<?= e($placeholder ?? '') ?>"
      <?= $reqAttr ?><?= $maxAttr ?><?= $minAttr ?>
    />
  <?php endif; ?>
  <?php if ($error): ?>
    <p class="text-xs text-red-600 mt-1" role="alert"><?= $error ?></p>
  <?php endif; ?>
</div>
