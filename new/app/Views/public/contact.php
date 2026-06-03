<section class="bg-brand-navy text-white py-16">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <h1 class="text-4xl font-semibold tracking-tight">Contact us</h1>
    <p class="text-slate-300 mt-4">General questions, partnerships, and company information.</p>
  </div>
</section>

<section class="py-16">
  <div class="max-w-5xl mx-auto px-4">
    <?php if (!empty($success)): ?>
      <div class="mb-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-8 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card mb-10 bg-brand-gold-muted/40 border-brand-gold/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 md:p-8">
      <div>
        <h2 class="font-semibold text-brand-navy text-lg">Need pricing or service availability?</h2>
        <p class="text-sm text-slate-600 mt-1">Use our dedicated quote request form for faster B2B follow-up.</p>
      </div>
      <a href="<?= url('quote') ?>" class="btn-primary shrink-0">Request a quote</a>
    </div>

    <div class="grid lg:grid-cols-5 gap-12">
      <aside class="lg:col-span-2 space-y-6">
        <div class="card">
          <h2 class="font-semibold text-brand-navy mb-4">BlueAxis Logistics & Warehousing</h2>
          <ul class="text-sm text-slate-600 space-y-3">
            <li>
              <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Address</span>
              <?= e($contact['company_address'] ?? 'Winnipeg, Manitoba, Canada') ?>
            </li>
            <li>
              <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Email</span>
              <a href="mailto:<?= e($contact['company_email'] ?? 'info@blueaxis.com') ?>" class="text-brand-navy font-medium hover:text-brand-gold"><?= e($contact['company_email'] ?? 'info@blueaxis.com') ?></a>
            </li>
            <li>
              <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Phone</span>
              <?= e($contact['company_phone'] ?? '') ?>
            </li>
          </ul>
        </div>
      </aside>

      <div class="lg:col-span-3">
        <div class="card">
          <h2 class="text-xl font-semibold text-brand-navy mb-2">Send a message</h2>
          <p class="text-sm text-slate-600 mb-6">For non-commercial inquiries, media, or other questions—not for quote requests.</p>
          <form method="post" action="<?= url('contact') ?>" class="grid gap-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Name *</label>
                <input name="name" required class="input-field" value="<?= old('name') ?>" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Company</label>
                <input name="company" class="input-field" value="<?= old('company') ?>" />
              </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Email *</label>
                <input type="email" name="email" required class="input-field" value="<?= old('email') ?>" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input name="phone" type="tel" class="input-field" value="<?= old('phone') ?>" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Message *</label>
              <textarea name="message" rows="5" required class="input-field" placeholder="How can we help?"><?= old('message') ?></textarea>
            </div>
            <button type="submit" class="btn-primary w-fit">Send message</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
