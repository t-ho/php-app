<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="h3 mb-0">All Posts</h2>
  <div class="text-muted">
    <?php if (!empty($search)) : ?>
      Search results for "<?= e($search) ?>"
    <?php endif; ?>
  </div>
</div>

<div class="row mb-4">
  <div class="col-md-6 offset-md-6">
    <form action="" method="GET" class="d-flex">
      <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?= e($search) ?>">
      <button type="submit" class="btn btn-outline-primary">Search</button>
      <?php if (!empty($search)) : ?>
        <a href="/posts" class="btn btn-outline-secondary ms-2">Clear</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php if (empty($posts)) : ?>
  <div class="alert alert-info text-center">
    <?php if (!empty($search)) : ?>
      <h4>No posts found</h4>
      <p>No posts match your search criteria. Try different keywords or <a href="/posts">view all posts</a>.</p>
    <?php else : ?>
      <h4>No posts yet</h4>
      <p>There are no posts to display at the moment. Check back later!</p>
    <?php endif; ?>
  </div>
<?php else : ?>
  <?= partial('_posts', ['posts' => $posts]) ?>
  <?= partial('_pagination', ['currentPage' => $currentPage, 'totalPages' => $totalPages]) ?>
<?php endif; ?>
