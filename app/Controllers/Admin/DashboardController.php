<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Authorization;

class DashboardController extends BaseController
{
    public function index()
    {
        Authorization::ensureAuthorized('access_dashboard');

        $totalPosts = Post::count();
        $totalComments = Comment::count();

        $recentPosts = Post::getRecent(5);
        $recentComments = Comment::getRecent(5);

        return $this->renderView(
            template: 'admin/dashboard/index',
            data: [
                'totalPosts' => $totalPosts,
                'totalComments' => $totalComments,
                'recentPosts' => $recentPosts,
                'recentComments' => $recentComments,
            ],
            layout: 'layouts/admin'
        );
    }
}
