<?php $s = $settings;
$checked = static fn (string $key): string => ($s[$key] ?? '0') === '1' ? 'checked' : '';
?>
<div class="max-w-2xl">
  <p class="text-sm text-slate-600 mb-8">
    Configure how lead notifications are sent. Settings are stored in the database and override <code class="text-xs bg-slate-100 px-1 rounded">.env</code> values.
  </p>

  <form method="post" action="<?= url('admin/settings/email') ?>" class="space-y-8">
    <?= \App\Core\Csrf::field() ?>

    <fieldset class="card space-y-4">
      <legend class="font-semibold text-brand-navy">Delivery method</legend>
      <div>
        <label class="block text-sm font-medium mb-1">Driver</label>
        <select name="mail_driver" class="input-field max-w-xs">
          <option value="mail" <?= $s['mail_driver'] === 'mail' ? 'selected' : '' ?>>PHP mail()</option>
          <option value="smtp" <?= $s['mail_driver'] === 'smtp' ? 'selected' : '' ?>>SMTP</option>
        </select>
      </div>
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">SMTP host</label>
          <input name="mail_host" value="<?= e($s['mail_host']) ?>" class="input-field" placeholder="smtp.gmail.com" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Port</label>
          <input name="mail_port" value="<?= e($s['mail_port']) ?>" class="input-field" type="number" />
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Encryption</label>
        <select name="mail_encryption" class="input-field max-w-xs">
          <option value="tls" <?= $s['mail_encryption'] === 'tls' ? 'selected' : '' ?>>TLS (STARTTLS)</option>
          <option value="ssl" <?= $s['mail_encryption'] === 'ssl' ? 'selected' : '' ?>>SSL</option>
          <option value="" <?= $s['mail_encryption'] === '' ? 'selected' : '' ?>>None</option>
        </select>
      </div>
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">SMTP username</label>
          <input name="mail_username" value="<?= e($s['mail_username']) ?>" class="input-field" autocomplete="off" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">SMTP password</label>
          <input type="password" name="mail_password" class="input-field" autocomplete="new-password"
                 placeholder="<?= $hasPassword ? '•••••••• (leave blank to keep current)' : 'Enter SMTP password' ?>" />
        </div>
      </div>
    </fieldset>

    <fieldset class="card space-y-4">
      <legend class="font-semibold text-brand-navy">Sender</legend>
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">From email *</label>
          <input name="mail_from_address" value="<?= e($s['mail_from_address']) ?>" class="input-field" type="email" required />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">From name</label>
          <input name="mail_from_name" value="<?= e($s['mail_from_name']) ?>" class="input-field" />
        </div>
      </div>
    </fieldset>

    <fieldset class="card space-y-4">
      <legend class="font-semibold text-brand-navy">Lead notifications</legend>
      <div>
        <label class="block text-sm font-medium mb-1">Send notifications to *</label>
        <input name="mail_notify_to" value="<?= e($s['mail_notify_to']) ?>" class="input-field" type="email" required />
        <p class="text-xs text-slate-500 mt-1">Contact and quote form submissions are emailed here.</p>
      </div>
      <div class="space-y-3">
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" name="mail_notify_contact" value="1" <?= $checked('mail_notify_contact') ?> />
          Email on new contact inquiry
        </label>
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" name="mail_notify_quote" value="1" <?= $checked('mail_notify_quote') ?> />
          Email on new quote request
        </label>
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" name="mail_reply_to_lead" value="1" <?= $checked('mail_reply_to_lead') ?> />
          Set Reply-To to visitor email (easier to respond)
        </label>
      </div>
    </fieldset>

    <button type="submit" class="btn-primary">Save email settings</button>
  </form>

  <form method="post" action="<?= url('admin/settings/email/test') ?>" class="card mt-8 space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <legend class="font-semibold text-brand-navy">Send test email</legend>
    <p class="text-sm text-slate-600">Save settings first, then send a test using the current configuration.</p>
    <div class="flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-[200px]">
        <label class="block text-sm font-medium mb-1">Recipient</label>
        <input name="test_email" type="email" class="input-field" placeholder="<?= e($s['mail_notify_to']) ?>" />
      </div>
      <button type="submit" class="btn-secondary">Send test</button>
    </div>
  </form>
</div>
