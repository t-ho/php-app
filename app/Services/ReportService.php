<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ReportService
{
    public function generatePdf(array $data): array
    {
        $html = $this->generateReportHtml($data);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return [
            'content' => $dompdf->output(),
            'filename' => 'dashboard-report_' . date('Y-m-d_H-i-s') . '.pdf'
        ];
    }

    private function generateReportHtml(array $data): string
    {
        $generatedDate = date('F j, Y \a\t g:i A');

        // Pre-escape data for safe output
        $escapedData = [
            'appName' => e($data['appName']),
            'totalPosts' => e($data['totalPosts']),
            'totalComments' => e($data['totalComments']),
            'totalViews' => e($data['totalViews']),
            'totalUsers' => e($data['totalUsers']),
            'avgViewsPerPost' => e($data['avgViewsPerPost']),
            'avgPostsPerUser' => e($data['avgPostsPerUser']),
            'avgCommentsPerUser' => e($data['avgCommentsPerUser']),
            'viewsPerComment' => e($data['viewsPerComment']),
            'chartImages' => $data['chartImages'] // is already sanitized
        ];

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .stats-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .stats-grid table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-grid td {
            width: 25%;
            padding: 10px;
        }
        .stat-card {
            background: #f8f9fa;
            padding-top: 16px;
            padding-bottom: 10px;
            padding-left: 8px;
            padding-right: 8px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
            height: 65px;
        }
        .stat-card.posts {
            background: #007bff;
            color: white;
            border: 1px solid #0056b3;
        }
        .stat-card.comments {
            background: #28a745;
            color: white;
            border: 1px solid #1e7e34;
        }
        .stat-card.views {
            background: #ffc107;
            color: #212529;
            border: 1px solid #e0a800;
        }
        .stat-card.users {
            background: #dc3545;
            color: white;
            border: 1px solid #b02a37;
        }
        .stat-number {
            font-size: 1.6em;
            font-weight: bold;
            margin: 0 0 4px 0;
            line-height: 1.1;
        }
        .stat-label {
            margin: 0;
            font-size: 0.85em;
            line-height: 1.1;
        }
        .stat-card.posts .stat-number,
        .stat-card.comments .stat-number,
        .stat-card.users .stat-number {
            color: white;
        }
        .stat-card.views .stat-number {
            color: #212529;
        }
        .stat-card.posts .stat-label,
        .stat-card.comments .stat-label,
        .stat-card.users .stat-label {
            color: white;
        }
        .stat-card.views .stat-label {
            color: #212529;
        }
        .chart-section {
            margin-bottom: 30px;
        }
        .chart-title {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #333;
        }
        .chart-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .chart-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Report</h1>
        <p>Generated on {$generatedDate}</p>
        <p style="margin: 5px 0 0 0; font-size: 0.9em;">{$escapedData['appName']}</p>
    </div>

    <div class="stats-grid">
        <table>
            <tr>
                <td>
                    <div class="stat-card posts">
                        <div class="stat-number">{$escapedData['totalPosts']}</div>
                        <div class="stat-label">Total Posts</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card comments">
                        <div class="stat-number">{$escapedData['totalComments']}</div>
                        <div class="stat-label">Total Comments</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card views">
                        <div class="stat-number">{$escapedData['totalViews']}</div>
                        <div class="stat-label">Total Views</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card users">
                        <div class="stat-number">{$escapedData['totalUsers']}</div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="chart-section">
        <h3 class="chart-title">Content Creation</h3>
        <div class="chart-container" style="text-align: center;">
            <img src="{$escapedData['chartImages']['contentCreation']}" alt="Content Creation Chart" style="width: 60%; height: auto;">
        </div>
    </div>

    <div class="chart-section">
        <h3 class="chart-title">Content & User Distribution</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center; padding: 10px 2px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 1em;">Content Distribution</h4>
                    <div style="padding: 10px 0;">
                        <img src="{$escapedData['chartImages']['contentDistribution']}" alt="Content Distribution Chart" style="width: 90%; height: auto;">
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center; padding: 10px 2px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 1em;">User Role Distribution</h4>
                    <div style="padding: 10px 0;">
                        <img src="{$escapedData['chartImages']['userRoleDistribution']}" alt="User Role Distribution Chart" style="width: 90%; height: auto;">
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-before: always;"></div>
    
    <div class="chart-section">
        <h3 class="chart-title">Most Popular Posts</h3>
        <div class="chart-container">
            <img src="{$escapedData['chartImages']['topViewedPosts']}" alt="Most Popular Posts Chart" class="chart-image">
        </div>
    </div>

    <div class="chart-section">
        <h3 class="chart-title">Additional Statistics</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 10px 2px;">
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #dee2e6; height: 180px;">
                        <h4 style="margin: 0 0 15px 0; font-size: 1.1em; color: #333;">Views Summary</h4>
                        <div style="font-size: 2em; font-weight: bold; color: #007bff; margin: 0 0 10px 0;">{$escapedData['totalViews']}</div>
                        <div style="margin: 0 0 15px 0; color: #666;">Total Page Views</div>
                        <hr style="margin: 15px 0; border: 0; border-top: 1px solid #dee2e6;">
                        <table style="width: 100%; height: 50px;">
                            <tr>
                                <td style="width: 50%; text-align: center; padding: 5px; vertical-align: middle;">
                                    <div style="font-size: 1.2em; font-weight: bold; color: #17a2b8;">{$escapedData['avgViewsPerPost']}</div>
                                    <div style="font-size: 0.8em; color: #666;">Avg per Post</div>
                                </td>
                                <td style="width: 50%; text-align: center; padding: 5px; vertical-align: middle;">
                                    <div style="font-size: 1.2em; font-weight: bold; color: #28a745;">{$escapedData['viewsPerComment']}</div>
                                    <div style="font-size: 0.8em; color: #666;">Views per Comment</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 10px 2px;">
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #dee2e6; height: 180px;">
                        <h4 style="margin: 0 0 15px 0; font-size: 1.1em; color: #333;">User Statistics</h4>
                        <div style="font-size: 2em; font-weight: bold; color: #ffc107; margin: 0 0 10px 0;">{$escapedData['totalUsers']}</div>
                        <div style="margin: 0 0 15px 0; color: #666;">Total Users</div>
                        <hr style="margin: 15px 0; border: 0; border-top: 1px solid #dee2e6;">
                        <table style="width: 100%; height: 50px;">
                            <tr>
                                <td style="width: 50%; text-align: center; padding: 5px; vertical-align: middle;">
                                    <div style="font-size: 1.2em; font-weight: bold; color: #17a2b8;">{$escapedData['avgPostsPerUser']}</div>
                                    <div style="font-size: 0.8em; color: #666;">Avg Posts per User</div>
                                </td>
                                <td style="width: 50%; text-align: center; padding: 5px; vertical-align: middle;">
                                    <div style="font-size: 1.2em; font-weight: bold; color: #28a745;">{$escapedData['avgCommentsPerUser']}</div>
                                    <div style="font-size: 0.8em; color: #666;">Avg Comments per User</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
    }
}
