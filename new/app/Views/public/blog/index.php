<section class="bg-brand-navy text-white py-16">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-4xl font-semibold">Insights & News</h1>
    <p class="text-slate-300 mt-2">Logistics, supply chain, and industry perspectives.</p>
  </div>
</section>

<section class="py-12">
  <div class="max-w-7xl mx-auto px-4">
    <form method="get" action="<?= url('blog') ?>" class="flex flex-wrap gap-4 mb-12">
      <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search articles…" class="input-field max-w-md" />
      <select name="category" class="input-field max-w-xs">
        <option value="">All categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= e($cat['slug']) ?>" <?= $categorySlug === $cat['slug'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn-primary">Search</button>
    </form>

    <?php if ($featured && $currentPage === 1 && !$search && !$categorySlug): ?>
      <article class="card mb-12 grid md:grid-cols-2 gap-8 p-0 overflow-hidden">
        <a href="<?= url('blog/' . $featured['slug']) ?>" class="contents">
          <div class="h-64 md:h-auto bg-brand-navy/10">
            <?php if ($featured['featured_image']): ?>
              <img src="<?= e(media_url($featured['featured_image'])) ?>" class="w-full h-full object-cover" alt="" />
            <?php endif; ?>
          </div>
          <div class="p-8 flex flex-col justify-center">
            <span class="text-xs font-semibold text-brand-gold uppercase">Featured</span>
            <h2 class="text-2xl font-semibold text-brand-navy mt-2"><?= e($featured['title']) ?></h2>
            <p class="text-slate-600 mt-3"><?= e($featured['excerpt']) ?></p>
          </div>
        </a>
      </article>
    <?php endif; ?>

    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ($posts as $post): ?>
        <article class="card p-0 overflow-hidden">
          <a href="<?= url('blog/' . $post['slug']) ?>">
            <div class="h-40 bg-slate-100">
              <?php if ($post['featured_image']): ?>
                <img src="<?= e(media_url($post['featured_image'])) ?>" class="w-full h-full object-cover" alt="" />
              <?php endif; ?>
            </div>
            <div class="p-6">
              <p class="text-xs text-brand-gold font-semibold"><?= e($post['category_name'] ?? '') ?></p>
              <h2 class="font-semibold text-brand-navy mt-2"><?= e($post['title']) ?></h2>
              <p class="text-sm text-slate-600 mt-2 line-clamp-2"><?= e($post['excerpt']) ?></p>
              <time class="text-xs text-slate-400 mt-4 block" datetime="<?= e($post['published_at']) ?>"><?= date('M j, Y', strtotime($post['published_at'])) ?></time>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
      <nav class="flex justify-center gap-2 mt-12" aria-label="Pagination">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="?page=<?= $p ?>&q=<?= urlencode($search) ?>&category=<?= urlencode($categorySlug) ?>"
             class="px-4 py-2 text-sm rounded-md <?= $p === $currentPage ? 'bg-brand-navy text-white' : 'bg-white border text-slate-700' ?>"><?= $p ?></a>
        <?php endfor; ?>
      </nav>
    <?php endif; ?>
  </div>
</section>
