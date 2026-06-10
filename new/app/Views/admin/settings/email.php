<?php
$s = $settings;
$checked = static fn (string $key): string => ($s[$key] ?? '0') === '1' ? 'checked' : '';
?>
<form method="post" action="<?= url('admin/settings/email') ?>" x-data="adminTabs('delivery')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'delivery' && 'active'" @click="tab = 'delivery'">How emails are sent</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'sender' && 'active'" @click="tab = 'sender'">Sender details</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'notifications' && 'active'" @click="tab = 'notifications'">Form alerts</button>
    </nav>

    <div class="admin-panel__body space-y-6 max-w-2xl">
      <div x-show="tab === 'delivery'" x-cloak class="space-y-5">
        <p class="admin-section-desc">Choose how the website sends email when someone submits a form.</p>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Sending method',
            'name' => 'mail_driver',
            'type' => 'select',
            'value' => $s['mail_driver'],
            'options' => ['mail' => 'Simple (server mail)', 'smtp' => 'SMTP (Gmail, Outlook, etc.)'],
        ]); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SMTP server', 'name' => 'mail_host', 'value' => $s['mail_host'], 'placeholder' => 'smtp.gmail.com']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Port', 'name' => 'mail_port', 'value' => $s['mail_port'], 'type' => 'number']); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Security',
            'name' => 'mail_encryption',
            'type' => 'select',
            'value' => $s['mail_encryption'],
            'options' => ['tls' => 'TLS (recommended)', 'ssl' => 'SSL', '' => 'None'],
        ]); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SMTP username', 'name' => 'mail_username', 'value' => $s['mail_username']]); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'SMTP password',
            'name' => 'mail_password',
            'type' => 'password',
            'placeholder' => $hasPassword ? 'Leave blank to keep current password' : 'Enter password',
        ]); ?>
      </div>

      <div x-show="tab === 'sender'" x-cloak class="space-y-5">
        <p class="admin-section-desc">What recipients see in the “From” line when your site sends email.</p>
        <?php \App\Core\View::partial('admin/field', ['label' => 'From email address', 'name' => 'mail_from_address', 'value' => $s['mail_from_address'], 'type' => 'email', 'required' => true]); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'From name', 'name' => 'mail_from_name', 'value' => $s['mail_from_name'], 'placeholder' => 'BlueAxis Website']); ?>
      </div>

      <div x-show="tab === 'notifications'" x-cloak class="space-y-6">
        <p class="admin-section-desc">Control team alerts and automatic confirmation emails for every public form.</p>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Send notifications to',
            'name' => 'mail_notify_to',
            'value' => $s['mail_notify_to'],
            'type' => 'email',
            'required' => true,
        ]); ?>

        <div>
          <h3 class="text-sm font-semibold text-brand-navy mb-3">Team alerts</h3>
          <p class="text-sm text-slate-500 mb-3">Email your team when a visitor submits a form.</p>
          <div class="space-y-3">
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_notify_contact" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_notify_contact') ?> />
              Contact form
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_notify_quote" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_notify_quote') ?> />
              Quote request form
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_notify_newsletter" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_notify_newsletter') ?> />
              Newsletter signup
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_notify_comment" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_notify_comment') ?> />
              Blog comments (pending moderation)
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700 pt-1 border-t border-slate-100">
              <input type="checkbox" name="mail_reply_to_lead" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_reply_to_lead') ?> />
              Set Reply-To to the visitor’s email on team alerts
            </label>
          </div>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-brand-navy mb-3">Visitor confirmations</h3>
          <p class="text-sm text-slate-500 mb-3">Automatic thank-you emails sent to the person who submitted the form.</p>
          <div class="space-y-3">
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_confirm_contact" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_confirm_contact') ?> />
              Contact form
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_confirm_quote" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_confirm_quote') ?> />
              Quote request form
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_confirm_newsletter" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_confirm_newsletter') ?> />
              Newsletter signup
            </label>
            <label class="flex items-center gap-3 text-sm text-slate-700">
              <input type="checkbox" name="mail_confirm_comment" value="1" class="rounded border-slate-300 text-brand-navy" <?= $checked('mail_confirm_comment') ?> />
              Blog comments
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <p class="text-sm text-slate-500">Save before sending a test email.</p>
      <button type="submit" class="btn-primary">Save email settings</button>
    </div>
  </div>
</form>

<div class="admin-panel admin-panel__body max-w-2xl mt-8">
  <h2 class="admin-section-title">Send a test email</h2>
  <p class="admin-section-desc">Confirm your settings work before going live.</p>
  <form method="post" action="<?= url('admin/settings/email/test') ?>" class="admin-inline-form mt-6">
    <?= \App\Core\Csrf::field() ?>
    <?php \App\Core\View::partial('admin/field', [
        'label' => 'Send test to',
        'name' => 'test_email',
        'type' => 'email',
        'placeholder' => $s['mail_notify_to'],
    ]); ?>
    <button type="submit" class="btn-secondary">Send test</button>
  </form>
</div>
