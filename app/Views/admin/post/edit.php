<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="h3 mb-0">Edit Post</h2>
  <div>
    <a href="/posts/<?= $post->id ?>?referer=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-outline-info me-2">View Post</a>
    <a href="/admin/posts" class="btn btn-outline-secondary">← Back to Posts</a>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <form action="/admin/posts/<?= $post->id ?>" method="POST">
          <?= csrf_token() ?>
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= e($post->title) ?>" required>
          </div>
          <div class="mb-4">
            <label for="content" class="form-label">Content</label>
            <?= partial('_tinymce', [
              'id' => 'content',
              'name' => 'content',
              'value' => $post->sanitized_html_content,
              'required' => true
            ]) ?>
          </div>
          <div class="d-flex justify-content-between">
            <a href="/admin/posts" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Post Stats</h5>
      </div>
      <div class="card-body">
        <p class="card-text">
          <strong>Views:</strong><br>
          <?= number_format($post->views) ?>
        </p>
        <p class="card-text">
          <strong>Created:</strong><br>
          <?= partial('_local_datetime', ['datetime' => $post->created_at ?? 'now']) ?>
        </p>
        <p class="card-text">
          <strong>Last Updated:</strong><br>
          <?= partial('_local_datetime', ['datetime' => $post->updated_at ?? 'now']) ?>
        </p>
      </div>
    </div>
  </div>
</div>
