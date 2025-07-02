<article>
  <h1><?= e($post->title); ?></h1>
  <p>Views: <?= $post->views ?></p>
  <p><?= nl2br(e($post->content)) ?></p>
</article>

<section>
  <h2>Comments</h2>

  <?php if ($user) : ?>
  <form action="/posts/<?= $post->id ?>/comments" method="POST">
      <?= csrf_token() ?>
    <textarea name="content" rows="4" cols="50" required></textarea>
    <button type="submit">Add Comment</button>
  </form>
  <?php else : ?>
    <p><a href="/login">Login to comment.</a></p>
  <?php endif; ?>

  <?php foreach ($comments as $comment) : ?>
    <div>
      <p><?= nl2br(e($comment->content)); ?></p>
      <small>Posted by User ID: <?= $comment->user_id ?> on <?= $comment->created_at ?></small>
    </div>
  <?php endforeach; ?>

</section>
