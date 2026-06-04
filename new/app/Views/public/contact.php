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
          <?php if (social_links() !== []): ?>
            <div class="mt-6 pt-6 border-t border-slate-100">
              <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">Follow us</span>
              <?php \App\Core\View::partial('social-links', ['variant' => 'light']); ?>
            </div>
          <?php endif; ?>
        </div>
      </aside>

      <div class="lg:col-span-3">
        <div class="card">
          <h2 class="text-xl font-semibold text-brand-navy mb-2">Send a message</h2>
          <p class="text-sm text-slate-600 mb-6">For non-commercial inquiries, media, or other questions—not for quote requests.</p>
          <form method="post" action="<?= url('contact') ?>" class="grid gap-4">
            <?= \App\Core\Csrf::field() ?>
            <div class="grid sm:grid-cols-2 gap-4">
              <?php \App\Core\View::partial('public/field', ['label' => 'Name', 'name' => 'name', 'required' => true, 'maxlength' => 120]); ?>
              <?php \App\Core\View::partial('public/field', ['label' => 'Company', 'name' => 'company', 'maxlength' => 200]); ?>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <?php \App\Core\View::partial('public/field', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'required' => true, 'maxlength' => 255]); ?>
              <?php \App\Core\View::partial('public/field', ['label' => 'Phone', 'name' => 'phone', 'type' => 'tel', 'maxlength' => 24]); ?>
            </div>
            <?php \App\Core\View::partial('public/field', [
                'label' => 'Message',
                'name' => 'message',
                'type' => 'textarea',
                'required' => true,
                'minlength' => 10,
                'maxlength' => 5000,
                'placeholder' => 'How can we help?',
                'rows' => 5,
            ]); ?>
            <button type="submit" class="btn-primary w-fit">Send message</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php \App\Core\View::partial('contact-map', [
    'contact' => $contact,
    'mapEmbedUrl' => $mapEmbedUrl,
]); ?>
