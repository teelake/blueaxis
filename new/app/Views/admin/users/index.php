<?php \App\Core\View::partial('admin/toolbar', [
    'meta' => 'Roles control what each person can see and change in the admin.',
    'filterHtml' => null,
    'actions' => [
        ['label' => '+ Add team member', 'url' => url('admin/users/create'), 'class' => 'btn-primary'],
    ],
]); ?>

<div class="admin-table-wrap mb-10">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Last login</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td class="font-medium"><?= e($u['name']) ?></td>
          <td class="text-slate-600"><?= e($u['email']) ?></td>
          <td><?= e($u['role_name'] ?? '—') ?></td>
          <td class="text-slate-500"><?= !empty($u['last_login_at']) ? e(date('M j, Y', strtotime($u['last_login_at']))) : '—' ?></td>
          <td class="text-right">
            <a href="<?= url('admin/users/' . $u['id'] . '/edit') ?>" class="text-sm font-medium text-brand-navy hover:text-brand-gold">Edit</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
