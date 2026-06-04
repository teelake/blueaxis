<div class="admin-panel admin-panel__body max-w-lg">
  <p class="admin-section-desc mb-6">Choose a strong password you do not use elsewhere.</p>
  <form method="post" action="<?= url('admin/profile/password') ?>" class="space-y-5">
    <?= \App\Core\Csrf::field() ?>
    <?php \App\Core\View::partial('admin/field', ['label' => 'Current password', 'name' => 'current_password', 'type' => 'password', 'required' => true]); ?>
    <?php \App\Core\View::partial('admin/field', ['label' => 'New password', 'name' => 'new_password', 'type' => 'password', 'hint' => 'At least 8 characters.', 'required' => true]); ?>
    <?php \App\Core\View::partial('admin/field', ['label' => 'Confirm new password', 'name' => 'new_password_confirmation', 'type' => 'password', 'required' => true]); ?>
    <button type="submit" class="btn-primary">Update password</button>
  </form>
</div>
