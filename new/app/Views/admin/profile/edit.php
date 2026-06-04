<div class="admin-panel admin-panel__body max-w-lg">
  <form method="post" action="<?= url('admin/profile') ?>" class="space-y-5">
    <?= \App\Core\Csrf::field() ?>
    <?php \App\Core\View::partial('admin/field', ['label' => 'Your name', 'name' => 'name', 'value' => $admin['name'] ?? '', 'required' => true]); ?>
    <?php \App\Core\View::partial('admin/field', ['label' => 'Email address', 'name' => 'email', 'value' => $admin['email'] ?? '', 'type' => 'email', 'required' => true]); ?>
    <p class="text-sm text-slate-500">Role: <strong><?= e($admin['role_name'] ?? '') ?></strong></p>
    <button type="submit" class="btn-primary">Save profile</button>
  </form>
</div>
