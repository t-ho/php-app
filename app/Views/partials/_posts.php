
<?php foreach ($posts as $post) : ?>
  <article class="card mb-4">
    <div class="card-body">
      <h3 class="card-title h5">
        <a href="/posts/<?= $post->id ?>" class="text-decoration-none"><?= e($post->title); ?></a>
      </h3>
      <p class="card-text text-muted"><?= e(generateExcerpt($post->sanitized_html_content, 150)) ?></p>
      <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">
          <?= partial('_local_date', ['datetime' => $post->created_at ?? 'now']) ?>
        </small>
        <a href="/posts/<?= $post->id ?>" class="btn btn-outline-primary btn-sm">Read More</a>
      </div>
    </div>
  </article>
<?php endforeach; ?>
