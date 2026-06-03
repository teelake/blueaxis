<form method="post" action="<?= url('admin/profile/password') ?>" class="max-w-lg card space-y-4">
  <?= \App\Core\Csrf::field() ?>
  <div>
    <label class="block text-sm font-medium mb-1">Current password</label>
    <input type="password" name="current_password" required class="input-field" autocomplete="current-password" />
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">New password</label>
    <input type="password" name="new_password" required minlength="8" class="input-field" autocomplete="new-password" />
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Confirm new password</label>
    <input type="password" name="new_password_confirmation" required minlength="8" class="input-field" autocomplete="new-password" />
  </div>
  <button type="submit" class="btn-primary">Update password</button>
</form>
