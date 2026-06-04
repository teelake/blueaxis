<form method="post" action="<?= $user ? url('admin/users/' . $user['id']) : url('admin/users') ?>" class="admin-panel admin-panel__body max-w-lg space-y-5">
  <?= \App\Core\Csrf::field() ?>
  <?php \App\Core\View::partial('admin/field', [
      'label' => 'Full name',
      'name' => 'name',
      'value' => $user['name'] ?? '',
      'required' => true,
  ]); ?>
  <?php \App\Core\View::partial('admin/field', [
      'label' => 'Email address',
      'name' => 'email',
      'type' => 'email',
      'value' => $user['email'] ?? '',
      'required' => true,
  ]); ?>
  <div class="admin-field">
    <label class="admin-label" for="role_id">Role</label>
    <p class="admin-hint">Super Admin has full access. Content Manager cannot change email settings or manage users.</p>
    <select id="role_id" name="role_id" class="admin-select" required>
      <?php foreach ($roles as $role): ?>
        <option value="<?= (int) $role['id'] ?>" <?= ($user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>><?= e($role['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php \App\Core\View::partial('admin/field', [
      'label' => $user ? 'New password (optional)' : 'Password',
      'name' => 'password',
      'type' => 'password',
      'hint' => 'Minimum 8 characters.' . ($user ? ' Leave blank to keep current password.' : ''),
      'required' => !$user,
  ]); ?>
  <?php if ($user): ?>
    <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
      <input type="checkbox" name="is_active" class="rounded border-slate-300 text-brand-navy" <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?> />
      Account is active (can sign in)
    </label>
  <?php endif; ?>
  <div class="flex gap-3 pt-2">
    <a href="<?= url('admin/users') ?>" class="btn-secondary">Cancel</a>
    <button type="submit" class="btn-primary"><?= $user ? 'Save changes' : 'Create account' ?></button>
  </div>
</form>
