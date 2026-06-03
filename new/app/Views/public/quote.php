<section class="bg-brand-navy text-white py-16 lg:py-20">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <p class="section-eyebrow text-brand-gold-light mb-3">B2B partnerships</p>
    <h1 class="text-4xl font-semibold tracking-tight">Request a quote</h1>
    <p class="text-slate-300 mt-4 text-lg leading-relaxed">
      Tell us about your import, warehousing, or distribution needs. Our team will review your request and respond with a tailored proposal.
    </p>
  </div>
</section>

<section class="py-16">
  <div class="max-w-2xl mx-auto px-4">
    <?php if (!empty($success)): ?>
      <div class="mb-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-8 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card">
      <form method="post" action="<?= url('quote') ?>" class="grid gap-5">
        <?= \App\Core\Csrf::field() ?>
        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Full name *</label>
            <input name="name" required class="input-field" value="<?= old('name') ?>" autocomplete="name" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Company *</label>
            <input name="company" required class="input-field" value="<?= old('company') ?>" autocomplete="organization" />
          </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Business email *</label>
            <input type="email" name="email" required class="input-field" value="<?= old('email') ?>" autocomplete="email" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
            <input name="phone" type="tel" class="input-field" value="<?= old('phone') ?>" autocomplete="tel" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Service required *</label>
          <select name="service_needed" required class="input-field">
            <option value="">Select a service</option>
            <?php foreach ($services as $svc): ?>
              <option value="<?= e($svc['title']) ?>" <?= (($_SESSION['_old']['service_needed'] ?? '') === $svc['title']) ? 'selected' : '' ?>><?= e($svc['title']) ?></option>
            <?php endforeach; ?>
            <option value="Multiple services">Multiple services</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Project details</label>
          <p class="text-xs text-slate-500 mb-2">Volumes, timelines, product categories, or delivery regions help us quote accurately.</p>
          <textarea name="message" rows="5" class="input-field" placeholder="Brief description of your requirements…"><?= old('message') ?></textarea>
        </div>
        <button type="submit" class="btn-primary w-full sm:w-auto">Submit quote request</button>
      </form>
    </div>

    <p class="text-center text-sm text-slate-600 mt-8">
      Not ready for a quote?
      <a href="<?= url('contact') ?>" class="font-semibold text-brand-navy hover:text-brand-gold">Contact us</a>
      for general questions.
    </p>
  </div>
</section>
