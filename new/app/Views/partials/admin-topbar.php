<?php use App\Core\Auth; $user = Auth::user(); ?>
<header class="admin-topbar">
  <div class="min-w-0">
    <?php if (empty($pageDescription)): ?>
      <h1 class="text-lg font-semibold text-slate-900 truncate"><?= e($title ?? 'Admin') ?></h1>
    <?php endif; ?>
  </div>
  <div class="flex items-center gap-3 shrink-0">
    <a href="<?= url('/') ?>" target="_blank" rel="noopener noreferrer" class="admin-view-site" title="Open the public website">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
      <span class="hidden sm:inline">View website</span>
    </a>
  <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
    <button
      type="button"
      @click="open = !open"
      class="flex items-center gap-2.5 rounded-full border border-slate-200 bg-white pl-1.5 pr-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition shadow-sm"
      :aria-expanded="open"
    >
      <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-navy text-xs font-semibold text-white">
        <?= e(strtoupper(substr((string) ($user['name'] ?? 'A'), 0, 1))) ?>
      </span>
      <span class="hidden sm:inline max-w-[120px] truncate"><?= e($user['name'] ?? 'Admin') ?></span>
      <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div
      x-show="open"
      x-cloak
      @click.outside="open = false"
      x-transition
      class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white py-1 shadow-elevated z-50"
    >
      <p class="px-4 py-2.5 text-xs text-slate-500 border-b border-slate-100">
        <span class="block truncate"><?= e($user['email'] ?? '') ?></span>
        <span class="block text-slate-400 mt-0.5"><?= e($user['role_name'] ?? $user['role'] ?? '') ?></span>
      </p>
      <a href="<?= url('admin/profile') ?>" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Edit profile</a>
      <a href="<?= url('admin/profile/password') ?>" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Change password</a>
      <div class="border-t border-slate-100 mt-1 pt-1">
        <form method="post" action="<?= url('admin/logout') ?>">
          <?= \App\Core\Csrf::field() ?>
          <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">Sign out</button>
        </form>
      </div>
    </div>
  </div>
  </div>
</header>
