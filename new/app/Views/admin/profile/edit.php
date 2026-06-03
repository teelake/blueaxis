<form method="post" action="<?= url('admin/profile') ?>" class="max-w-lg card space-y-4">
  <?= \App\Core\Csrf::field() ?>
  <div>
    <label class="block text-sm font-medium mb-1">Name</label>
    <input name="name" value="<?= e($admin['name'] ?? '') ?>" required class="input-field" />
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Email</label>
    <input type="email" name="email" value="<?= e($admin['email'] ?? '') ?>" required class="input-field" autocomplete="username" />
  </div>
  <p class="text-xs text-slate-500">Role: <?= e($admin['role_name'] ?? '') ?></p>
  <button type="submit" class="btn-primary">Save profile</button>
</form>
