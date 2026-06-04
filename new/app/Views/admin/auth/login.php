<div class="admin-login-card">
  <div class="text-center mb-8">
    <img src="<?= asset('images/BLUEAXIS_logo.png') ?>" alt="BlueAxis" class="h-12 mx-auto mb-4" />
    <h1 class="text-xl font-semibold text-slate-900">Sign in to CMS</h1>
    <p class="text-sm text-slate-500 mt-1">Manage your website content securely</p>
  </div>
  <?php if (!empty($error)): ?>
    <div class="admin-alert admin-alert--error mb-4"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= url('admin/login') ?>" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <?php \App\Core\View::partial('admin/field', [
        'label' => 'Email address',
        'name' => 'email',
        'type' => 'email',
        'placeholder' => 'admin@blueaxis.com',
        'required' => true,
    ]); ?>
    <?php \App\Core\View::partial('admin/field', [
        'label' => 'Password',
        'name' => 'password',
        'type' => 'password',
        'required' => true,
    ]); ?>
    <button type="submit" class="btn-primary w-full mt-2" data-loading-text="Signing in…">Sign in</button>
  </form>
</div>
