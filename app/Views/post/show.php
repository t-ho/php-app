<div class="row">
  <div class="col-md-8">
    <article class="card mb-4">
      <div class="card-body">
        <h1 class="card-title"><?= e($post->title); ?></h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <small class="text-muted">
            <?= date('F j, Y', strtotime($post->created_at ?? 'now')) ?>
          </small>
          <small class="text-muted">
            <i class="bi bi-eye"></i> <?= $post->views ?> views
          </small>
        </div>
        <div class="card-text">
          <?= $post->content ?>
        </div>
      </div>
    </article>

    <section class="card">
      <div class="card-header">
        <h3 class="card-title h5 mb-0">Comments (<?= count($comments) ?>)</h3>
      </div>
      <div class="card-body">
        <?php if ($user && isAuthorizedFor('create_comment')) : ?>
          <form action="/posts/<?= $post->id ?>/comments" method="POST" class="mb-4">
            <?= csrf_token() ?>
            <div class="mb-3">
              <label for="content" class="form-label">Add a comment</label>
              <textarea name="content" id="content" class="form-control" rows="4" placeholder="Share your thoughts..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Comment</button>
          </form>
        <?php else : ?>
          <div class="alert alert-info">
            <a href="/login" class="alert-link">Login</a> to join the conversation.
          </div>
        <?php endif; ?>

        <?php if (empty($comments)) : ?>
          <div class="text-center text-muted py-4">
            <p>No comments yet. Be the first to share your thoughts!</p>
          </div>
        <?php else : ?>
          <?php foreach ($comments as $comment) : ?>
            <div class="border-bottom py-3">
              <div class="mb-2">
                <?= nl2br(e($comment->content)); ?>
              </div>
              <small class="text-muted">
                Posted by <?= e($comment->user_name ?? 'Unknown User') ?> on <?= date('F j, Y', strtotime($comment->created_at ?? 'now')) ?>
              </small>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Post Info</h5>
      </div>
      <div class="card-body">
        <p class="card-text">
          <strong>Published:</strong><br>
          <?= date('F j, Y g:i A', strtotime($post->created_at ?? 'now')) ?>
        </p>
        <p class="card-text">
          <strong>Views:</strong><br>
          <?= number_format($post->views) ?>
        </p>
        <a href="/posts" class="btn btn-outline-secondary btn-sm">← Back to Posts</a>
      </div>
    </div>
  </div>
</div>
