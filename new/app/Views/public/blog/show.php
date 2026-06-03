<article>
  <section class="bg-brand-navy text-white py-16">
    <div class="max-w-3xl mx-auto px-4">
      <p class="text-brand-gold-light text-sm font-semibold uppercase"><?= e($post['category_name'] ?? '') ?></p>
      <h1 class="text-3xl md:text-4xl font-semibold mt-3"><?= e($post['title']) ?></h1>
      <p class="text-slate-400 text-sm mt-4">
        By <?= e($post['author_name'] ?? 'BlueAxis Team') ?> ·
        <time datetime="<?= e($post['published_at']) ?>"><?= date('F j, Y', strtotime($post['published_at'])) ?></time>
      </p>
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
