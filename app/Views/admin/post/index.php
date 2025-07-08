<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="h3 mb-0">Manage Posts</h2>
  <a href="/admin/posts/create" class="btn btn-primary">Create New Post</a>
</div>

<div class="row mb-4">
  <div class="col-md-6 offset-md-6">
    <form action="" method="GET" class="d-flex">
      <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?= e($search ?? '') ?>">
      <button type="submit" class="btn btn-outline-primary">Search</button>
      <?php if (!empty($search)) : ?>
        <a href="/admin/posts" class="btn btn-outline-secondary ms-2">Clear</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php if (empty($posts)) : ?>
  <div class="alert alert-info text-center">
    <h4>No posts yet</h4>
    <p>No posts have been created yet. <a href="/admin/posts/create">Create your first post</a> to get started!</p>
  </div>
<?php else : ?>
  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-dark">
          <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Created By</th>
            <th style="min-width: 160px; white-space: nowrap;">Created At</th>
            <th style="min-width: 160px; white-space: nowrap;">Updated At</th>
            <th class="text-center" style="min-width: 200px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $post) : ?>
            <tr>
              <td>
                <strong><?= e($post->title) ?></strong>
                <br>
                <small class="text-muted"><?= $post->views ?> views</small>
              </td>
              <td><?= e(generateExcerpt($post->sanitized_html_content, 100)) ?></td>
              <td>
                <small><?= e($post->user_name ?? 'Unknown') ?></small>
              </td>
              <td style="white-space: nowrap;">
                <small><?= partial('_local_datetime', ['datetime' => $post->created_at ?? 'now']) ?></small>
              </td>
              <td style="white-space: nowrap;">
                <small><?= partial('_local_datetime', ['datetime' => $post->updated_at ?? 'now']) ?></small>
              </td>
              <td class="text-center">
                <a href="/posts/<?= $post->id ?>?referer=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-outline-info btn-sm me-1" title="View">
                  View
                </a>
                <a href="/admin/posts/<?= $post->id ?>/edit" class="btn btn-outline-primary btn-sm me-1" title="Edit">
                  Edit
                </a>
                <form action="/admin/posts/<?= $post->id ?>/delete" method="POST" style="display:inline;">
                  <?= csrf_token() ?>
                  <button
                    type="submit"
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this post?');"
                    title="Delete"
                    nonce="<?= csp_nonce() ?>">
                    Delete
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <div class="mt-4">
    <?= partial('_pagination', ['currentPage' => $currentPage, 'totalPages' => $totalPages]) ?>
  </div>
<?php endif; ?>
