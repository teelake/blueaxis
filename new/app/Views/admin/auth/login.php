<div class="w-full max-w-md">
  <img src="<?= asset('images/BLUEAXIS_logo.png') ?>" alt="BlueAxis" class="h-16 mx-auto mb-8 brightness-0 invert" />
  <div class="bg-white rounded-xl shadow-elevated p-8">
    <h1 class="text-xl font-semibold text-brand-navy mb-6">Admin sign in</h1>
    <?php if (!empty($error)): ?><p class="mb-4 text-sm text-red-600"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="<?= url('admin/login') ?>" class="space-y-4">
      <?= \App\Core\Csrf::field() ?>
      <div><label class="block text-sm font-medium mb-1">Email</label><input type="email" name="email" required class="input-field" placeholder="admin@blueaxis.com" autocomplete="username" /></div>
      <div><label class="block text-sm font-medium mb-1">Password</label><input type="password" name="password" required class="input-field" /></div>
      <button type="submit" class="btn-primary w-full">Sign in</button>
    </form>
  </div>
</div>
