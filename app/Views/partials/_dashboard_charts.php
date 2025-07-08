<!-- Dashboard Charts Section -->
<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Content Creation</h5>
            </div>
            <div class="card-body">
                <canvas id="contentCreationBarChart" width="400" height="200" 
                        data-total-posts="<?= $totalPosts ?>" 
                        data-total-comments="<?= $totalComments ?>"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Content Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="contentDistributionPieChart" width="200" height="200" 
                        data-total-posts="<?= $totalPosts ?>" 
                        data-total-comments="<?= $totalComments ?>"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Views Summary</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h2 class="text-primary mb-2"><?= number_format($totalViews) ?></h2>
                    <p class="text-muted mb-2">Total Page Views</p>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-info"><?= $totalPosts > 0 ? number_format($totalViews / $totalPosts, 1) : '0' ?></h6>
                            <small class="text-muted">Avg per Post</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success"><?= $totalComments > 0 ? number_format($totalViews / $totalComments, 1) : '0' ?></h6>
                            <small class="text-muted">Views per Comment</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Most Popular Posts</h5>
            </div>
            <div class="card-body">
                <canvas id="popularPostsBarChart" width="400" height="200" 
                        data-top-viewed-posts='<?= htmlspecialchars(json_encode($topViewedPosts), ENT_QUOTES, 'UTF-8') ?>'></canvas>
            </div>
        </div>
    </div>
</div>

