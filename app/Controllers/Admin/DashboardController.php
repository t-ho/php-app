<?php

namespace App\Controllers\Admin;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\Authorization;

class DashboardController extends AdminBaseController
{
    private const RECENT_ITEMS_LIMIT = 5;

    public function index(array $params)
    {
        Authorization::ensureAuthorized('access_dashboard');

        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $totalViews = Post::getTotalViews();
        $totalUsers = User::count();

        $recentPosts = Post::getRecent(self::RECENT_ITEMS_LIMIT);
        $recentComments = Comment::getRecent(self::RECENT_ITEMS_LIMIT);
        $topViewedPosts = Post::getTopViewedPostsForChart();
        $userRoleDistribution = User::getRoleDistributionForChart();

        return $this->renderView(
            template: 'admin/dashboard/index',
            data: [
                'title' => 'Dashboard',
                'totalPosts' => $totalPosts,
                'totalComments' => $totalComments,
                'totalViews' => $totalViews,
                'totalUsers' => $totalUsers,
                'recentPosts' => $recentPosts,
                'recentComments' => $recentComments,
                'topViewedPosts' => $topViewedPosts,
                'userRoleDistribution' => $userRoleDistribution,
            ],
        );
    }
}
