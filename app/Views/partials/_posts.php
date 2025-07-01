
<?php foreach ($posts as $post) : ?>
  <article>
    <h3><a href="/posts/<?= $post->id ?>"><?= e($post->title); ?></a></h3>
    <p><?= e(substr($post->content, 0, 150)) ?>...</p>
  </article>
<?php endforeach; ?>
