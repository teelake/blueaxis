<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
  <p class="text-sm text-slate-600">Roles control what each person can see and change in the admin.</p>
  <a href="<?= url('admin/users/create') ?>" class="btn-primary">+ Add team member</a>
</div>

<div class="admin-table-wrap mb-10">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Last sign-in</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td class="font-medium text-slate-900"><?= e($u['name']) ?><?= (int) $u['id'] === (int) (\App\Core\Auth::id() ?? 0) ? ' <span class="text-xs text-slate-400">(you)</span>' : '' ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['role_name']) ?></td>
          <td>
            <span class="admin-badge <?= $u['is_active'] ? 'admin-badge--published' : 'admin-badge--draft' ?>">
              <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td class="text-slate-500 text-sm"><?= $u['last_login_at'] ? e(date('M j, Y g:ia', strtotime($u['last_login_at']))) : '—' ?></td>
          <td><a href="<?= url('admin/users/' . $u['id'] . '/edit') ?>" class="text-sm font-semibold text-brand-navy hover:text-brand-gold">Edit</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="admin-panel admin-panel__body">
  <h2 class="admin-section-title">Role permissions</h2>
  <p class="admin-section-desc mb-6">What each role can do in the admin (enforced on every page).</p>
  <div class="overflow-x-auto">
    <table class="admin-table text-sm">
      <thead>
        <tr>
          <th class="text-left">Permission</th>
          <?php foreach ($roles as $role): ?>
            <th class="text-center"><?= e($role['name']) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($permissionLabels as $perm => $label): ?>
          <tr>
            <td><?= e($label) ?></td>
            <?php foreach ($roles as $role): ?>
              <td class="text-center">
                <?= in_array($perm, $roleMatrix[$role['slug']] ?? [], true) || $role['slug'] === 'super_admin' ? '✓' : '—' ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
