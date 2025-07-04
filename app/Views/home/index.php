<div class="row">
  <div class="col-md-8">
    <div class="mb-4">
      <h2 class="h3 mb-4">Recent Posts</h2>
      <?php if (empty($posts)) : ?>
        <div class="alert alert-info">
          <h4>Welcome to the Blog!</h4>
          <p>No posts have been published yet. Check back soon for exciting content!</p>
        </div>
      <?php else : ?>
        <?= partial('_posts', ['posts' => $posts]) ?>
      <?php endif; ?>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">About</h5>
      </div>
      <div class="card-body">
        <p class="card-text">Welcome to our blog! Here you'll find the latest posts about web development, programming tips, and technology insights.</p>
      </div>
    </div>
  </div>
</div>
