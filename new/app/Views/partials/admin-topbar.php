<?php use App\Core\Auth; $user = Auth::user(); ?>
<header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between gap-4 shrink-0">
  <h1 class="text-lg font-semibold text-brand-navy truncate"><?= e($title ?? 'Admin') ?></h1>
  <div class="relative shrink-0" x-data="{ open: false }" @keydown.escape.window="open = false">
    <button
      type="button"
      @click="open = !open"
      class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-brand-navy hover:bg-slate-100 transition"
      :aria-expanded="open"
      aria-haspopup="true"
    >
      <span class="hidden sm:inline max-w-[140px] truncate"><?= e($user['name'] ?? 'Admin') ?></span>
      <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-navy text-xs font-semibold text-white">
        <?= e(strtoupper(substr((string) ($user['name'] ?? 'A'), 0, 1))) ?>
      </span>
      <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </button>
    <div
      x-show="open"
      x-cloak
      @click.outside="open = false"
      x-transition
      class="absolute right-0 mt-2 w-52 rounded-lg border border-slate-200 bg-white py-1 shadow-elevated z-50"
      role="menu"
    >
      <p class="px-4 py-2 text-xs text-slate-500 border-b border-slate-100 truncate"><?= e($user['email'] ?? '') ?></p>
      <a href="<?= url('admin/profile') ?>" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50" role="menuitem">Edit profile</a>
      <a href="<?= url('admin/profile/password') ?>" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50" role="menuitem">Change password</a>
      <div class="border-t border-slate-100 mt-1 pt-1">
        <form method="post" action="<?= url('admin/logout') ?>">
          <?= \App\Core\Csrf::field() ?>
          <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50" role="menuitem">Sign out</button>
        </form>
      </div>
    </div>
  </div>
</header>
