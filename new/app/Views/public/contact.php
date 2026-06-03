<section class="bg-brand-navy text-white py-16">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-4xl font-semibold">Contact Us</h1>
    <p class="text-slate-300 mt-2">Partnership inquiries and quote requests for B2B logistics.</p>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4">
    <?php if (!empty($success)): ?>
      <div class="mb-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-8 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-12">
      <div class="space-y-6">
        <div class="card">
          <h2 class="font-semibold text-brand-navy mb-4">Company</h2>
          <ul class="text-sm text-slate-600 space-y-2">
            <li><?= e($contact['company_address'] ?? '') ?></li>
            <li><a href="mailto:<?= e($contact['company_email'] ?? '') ?>" class="text-brand-navy font-medium"><?= e($contact['company_email'] ?? '') ?></a></li>
            <li><?= e($contact['company_phone'] ?? '') ?></li>
          </ul>
        </div>
      </div>

      <div class="lg:col-span-2 space-y-12">
        <div class="card" id="contact">
          <h2 class="text-xl font-semibold text-brand-navy mb-6">General inquiry</h2>
          <form method="post" action="<?= url('contact') ?>" class="grid gap-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid sm:grid-cols-2 gap-4">
              <div><label class="block text-sm font-medium mb-1">Name *</label><input name="name" required class="input-field" value="<?= old('name') ?>" /></div>
              <div><label class="block text-sm font-medium mb-1">Company</label><input name="company" class="input-field" value="<?= old('company') ?>" /></div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div><label class="block text-sm font-medium mb-1">Email *</label><input type="email" name="email" required class="input-field" value="<?= old('email') ?>" /></div>
              <div><label class="block text-sm font-medium mb-1">Phone</label><input name="phone" class="input-field" value="<?= old('phone') ?>" /></div>
            </div>
            <div><label class="block text-sm font-medium mb-1">Message *</label><textarea name="message" rows="5" required class="input-field"><?= old('message') ?></textarea></div>
            <button type="submit" class="btn-primary w-fit">Send message</button>
          </form>
        </div>

        <div class="card" id="quote">
          <h2 class="text-xl font-semibold text-brand-navy mb-6">Request a quote</h2>
          <form method="post" action="<?= url('quote') ?>" class="grid gap-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid sm:grid-cols-2 gap-4">
              <div><label class="block text-sm font-medium mb-1">Name *</label><input name="name" required class="input-field" value="<?= old('name') ?>" /></div>
              <div><label class="block text-sm font-medium mb-1">Company</label><input name="company" class="input-field" value="<?= old('company') ?>" /></div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div><label class="block text-sm font-medium mb-1">Email *</label><input type="email" name="email" required class="input-field" value="<?= old('email') ?>" /></div>
              <div><label class="block text-sm font-medium mb-1">Phone</label><input name="phone" class="input-field" value="<?= old('phone') ?>" /></div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Service needed *</label>
              <select name="service_needed" required class="input-field">
                <option value="">Select a service</option>
                <?php foreach ($services as $svc): ?>
                  <option value="<?= e($svc['title']) ?>"><?= e($svc['title']) ?></option>
                <?php endforeach; ?>
                <option value="Other">Other / Multiple services</option>
              </select>
            </div>
            <div><label class="block text-sm font-medium mb-1">Details</label><textarea name="message" rows="4" class="input-field"><?= old('message') ?></textarea></div>
            <button type="submit" class="btn-accent w-fit">Submit quote request</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
