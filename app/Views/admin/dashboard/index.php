<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard Overview</h2>
    <small class="text-muted">Welcome back, <?= e($user->name ?? 'Admin') ?>!</small>
</div>

<?= partial('_dashboard_stats', [
    'totalPosts' => $totalPosts,
    'totalComments' => $totalComments,
    'totalViews' => $totalViews
]) ?>

<?= partial('_dashboard_charts', [
    'totalPosts' => $totalPosts,
    'totalComments' => $totalComments,
    'totalViews' => $totalViews,
    'topViewedPosts' => $topViewedPosts
]) ?>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Posts</h5>
                <a href="/admin/posts" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recentPosts)) : ?>
                    <p class="text-muted">No posts yet.</p>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentPosts as $post) : ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <a href="/posts/<?= $post->id ?>" class="text-decoration-none">
                                            <?= e($post->title) ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted"><?= date('M j', strtotime($post->created_at)) ?></small>
                                </div>
                                <p class="mb-1 text-muted">
                                    <?= e(generateExcerpt($post->sanitized_html_content, 75)) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Comments</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentComments)) : ?>
                    <p class="text-muted">No comments yet.</p>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentComments as $comment) : ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <a href="/posts/<?= $comment->post_id ?>" class="text-decoration-none">
                                            Comment on Post #<?= $comment->post_id ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted"><?= date('M j', strtotime($comment->created_at)) ?></small>
                                </div>
                                <p class="mb-1 text-muted">
                                    <?= e(generateExcerpt($comment->content, 75)) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

