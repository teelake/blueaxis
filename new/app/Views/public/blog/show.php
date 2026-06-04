<article>
  <section class="bg-brand-navy text-white py-16">
    <div class="max-w-3xl mx-auto px-4">
      <p class="text-brand-gold-light text-sm font-semibold uppercase"><?= e($post['category_name'] ?? '') ?></p>
      <h1 class="text-3xl md:text-4xl font-semibold mt-3"><?= e($post['title']) ?></h1>
      <p class="text-slate-400 text-sm mt-4">
        By <?= e($post['author_name'] ?? 'BlueAxis Team') ?> ·
        <time datetime="<?= e($post['published_at']) ?>"><?= date('F j, Y', strtotime($post['published_at'])) ?></time>
      </p>
      <?php if (!empty($tags)): ?>
        <ul class="flex flex-wrap gap-2 mt-4">
          <?php foreach ($tags as $tag): ?>
            <li><span class="text-xs px-2 py-1 rounded-full bg-white/10 border border-white/20"><?= e($tag['name']) ?></span></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </section>
  <?php if ($post['featured_image']): ?>
    <div class="max-w-5xl mx-auto px-4 -mt-8">
      <img src="<?= e(media_url($post['featured_image'])) ?>" alt="" class="w-full rounded-xl shadow-elevated max-h-[420px] object-cover" />
    </div>
  <?php endif; ?>
  <div class="max-w-3xl mx-auto px-4 py-16 prose prose-lg prose-slate">
    <?= $post['content'] ?>
  </div>
  <div class="max-w-3xl mx-auto px-4 pb-8 flex gap-4">
    <?php
    $shareUrl = urlencode(url('blog/' . $post['slug']));
    $shareTitle = urlencode($post['title']);
    ?>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" target="_blank" rel="noopener" class="text-sm font-medium text-brand-navy">Share on LinkedIn</a>
    <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" rel="noopener" class="text-sm font-medium text-brand-navy">Share on X</a>
  </div>

  <section id="comments" class="max-w-3xl mx-auto px-4 pb-16 scroll-mt-24">
    <h2 class="text-xl font-semibold text-brand-navy mb-6">Comments</h2>
    <?php if (!empty($commentSuccess)): ?>
      <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm"><?= e($commentSuccess) ?></div>
    <?php endif; ?>
    <?php if (!empty($commentError)): ?>
      <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm"><?= e($commentError) ?></div>
    <?php endif; ?>

    <?php if (!empty($comments)): ?>
      <ul class="space-y-6 mb-10">
        <?php foreach ($comments as $c): ?>
          <li class="border-b border-slate-100 pb-6">
            <p class="font-semibold text-brand-navy text-sm"><?= e($c['author_name']) ?></p>
            <p class="text-xs text-slate-400 mb-2"><?= date('F j, Y', strtotime($c['created_at'])) ?></p>
            <p class="text-slate-700 leading-relaxed"><?= nl2br(e($c['body'])) ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="text-sm text-slate-500 mb-8">Be the first to leave a comment.</p>
    <?php endif; ?>

    <div class="card">
      <h3 class="font-semibold text-brand-navy mb-4">Leave a comment</h3>
      <form method="post" action="<?= url('blog/' . $post['slug'] . '/comments') ?>" class="space-y-4">
        <?= \App\Core\Csrf::field() ?>
        <div class="grid sm:grid-cols-2 gap-4">
          <?php \App\Core\View::partial('public/field', ['label' => 'Name', 'name' => 'author_name', 'required' => true, 'maxlength' => 120]); ?>
          <?php \App\Core\View::partial('public/field', ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'required' => true, 'maxlength' => 255]); ?>
        </div>
        <?php \App\Core\View::partial('public/field', [
            'label' => 'Comment',
            'name' => 'body',
            'type' => 'textarea',
            'required' => true,
            'minlength' => 3,
            'maxlength' => 2000,
            'placeholder' => 'Your comment (moderated before publishing)',
            'rows' => 4,
        ]); ?>
        <button type="submit" class="btn-primary">Submit comment</button>
      </form>
    </div>
  </section>

  <?php if ($related): ?>
    <section class="bg-slate-50 py-16">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="section-title mb-8">Related articles</h2>
        <div class="grid md:grid-cols-3 gap-6">
          <?php foreach ($related as $r): ?>
            <a href="<?= url('blog/' . $r['slug']) ?>" class="card hover:shadow-elevated transition">
              <h3 class="font-semibold text-brand-navy"><?= e($r['title']) ?></h3>
              <p class="text-sm text-slate-600 mt-2"><?= e(truncate($r['excerpt'] ?? '', 100)) ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</article>
