<?php

namespace App\Controllers\Admin;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\AuthorizationService;
use App\Services\ReportService;

class ReportController extends AdminBaseController
{
    private const MAX_IMAGE_SIZE_BYTES = 5 * 1024 * 1024; // 5MB
    private const ALLOWED_BASE64_MIME_TYPES = ['image/png', 'image/jpeg', 'image/jpg'];
    private const BASE64_FORMAT_PATTERN = '/^data:image\/(png|jpeg|jpg);base64,/';

    public function generateDashboardReport()
    {
        AuthorizationService::ensureAuthorized('access_dashboard');

        // Validate and sanitize input
        $chartData = $_POST['chartData'] ?? null;
        if (!$chartData) {
            $this->json(['error' => 'Chart data is required'], 400);
        }

        $chartData = json_decode($chartData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->json(['error' => 'Invalid JSON format'], 400);
        }

        // Validate chart data structure and sanitize base64 images
        $sanitizedChartData = $this->validateAndSanitizeChartData($chartData);
        if (!$sanitizedChartData) {
            $this->json(['error' => 'Invalid chart data format'], 400);
        }

        // Gather dashboard data
        $dashboardData = $this->gatherDashboardData($sanitizedChartData);

        try {
            $reportService = new ReportService();
            $report = $reportService->generatePdf($dashboardData);

            // Send PDF response
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $report['filename'] . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            echo $report['content'];
        } catch (\Exception $e) {
            $this->json(['error' => 'Failed to generate report'], 500);
        }
    }

    private function validateAndSanitizeChartData(array $chartData): ?array
    {
        $expectedKeys = ['contentCreation', 'contentDistribution', 'userRoleDistribution', 'topViewedPosts'];
        $sanitizedData = [];

        foreach ($expectedKeys as $key) {
            if (!isset($chartData[$key])) {
                return null; // Missing required chart
            }

            $base64Data = $chartData[$key];

            // Validate base64 format
            if (!is_string($base64Data) || !preg_match(self::BASE64_FORMAT_PATTERN, $base64Data)) {
                return null; // Invalid base64 image format
            }

            // Extract and validate the base64 content
            $base64Content = substr($base64Data, strpos($base64Data, ',') + 1);
            $decodedContent = base64_decode($base64Content, true);
            if ($decodedContent === false) {
                return null; // Invalid base64 encoding
            }

            // Check image size (prevent extremely large images)
            $decodedSize = strlen($decodedContent);
            if ($decodedSize > self::MAX_IMAGE_SIZE_BYTES) {
                return null; // Image too large
            }

            // Validate the decoded content is actually a valid image
            $imageInfo = getimagesizefromstring($decodedContent);
            if ($imageInfo === false) {
                return null; // Not a valid image
            }

            // Verify MIME type matches expected image types
            if (!in_array($imageInfo['mime'], self::ALLOWED_BASE64_MIME_TYPES)) {
                return null; // Invalid image type
            }

            // Add validated chart data to sanitized array
            $sanitizedData[$key] = $base64Data;
        }

        return $sanitizedData;
    }

    private function gatherDashboardData(array $chartData): array
    {
        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $totalViews = Post::getTotalViews();
        $totalUsers = User::count();
        $topViewedPosts = Post::getTopViewedPostsForChart();
        $userRoleDistribution = User::getRoleDistributionForChart();

        return [
            'totalPosts' => $totalPosts,
            'totalComments' => $totalComments,
            'totalViews' => $totalViews,
            'totalUsers' => $totalUsers,
            'topViewedPosts' => $topViewedPosts,
            'userRoleDistribution' => $userRoleDistribution,
            'chartImages' => $chartData,
            'avgViewsPerPost' => $totalPosts > 0 ? number_format($totalViews / $totalPosts, 1) : '0',
            'avgPostsPerUser' => $totalUsers > 0 ? number_format($totalPosts / $totalUsers, 1) : '0',
            'avgCommentsPerUser' => $totalUsers > 0 ? number_format($totalComments / $totalUsers, 1) : '0',
            'viewsPerComment' => $totalComments > 0 ? number_format($totalViews / $totalComments, 1) : '0',
            'appName' => config('app.name', 'Dashboard')
        ];
    }
}

