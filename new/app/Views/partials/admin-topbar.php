<?php use App\Core\Auth; ?>
<header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
  <h1 class="text-lg font-semibold text-brand-navy"><?= e($title ?? 'Admin') ?></h1>
  <span class="text-sm text-slate-500"><?= e(Auth::user()['name'] ?? '') ?></span>
</header>
