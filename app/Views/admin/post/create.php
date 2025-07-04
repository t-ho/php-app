<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="h3 mb-0">Create New Post</h2>
  <a href="/admin/posts" class="btn btn-outline-secondary">← Back to Posts</a>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <form action="/admin/posts" method="POST">
          <?= csrf_token() ?>
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title..." required>
          </div>
          <div class="mb-4">
            <label for="content" class="form-label">Content</label>
            <?= partial('_tinymce', [
              'id' => 'content',
              'name' => 'content',
              'placeholder' => 'Write your post content here...',
              'required' => true
            ]) ?>
          </div>
          <div class="d-flex justify-content-between">
            <a href="/admin/posts" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Publishing</h5>
      </div>
      <div class="card-body">
        <p class="card-text">Your post will be published immediately and visible to all visitors.</p>
        <small class="text-muted">Make sure to review your content before publishing.</small>
      </div>
    </div>
  </div>
</div>
