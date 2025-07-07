<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    /**
     * Specify seeder dependencies (UserSeeder must run first)
     */
    public function getDependencies(): array
    {
        return [
            'UserSeeder'
        ];
    }

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Welcome to Our Blog',
                'content' => '<h2>Getting Started</h2><p>Welcome to our amazing blog platform! This is your first post to get you started.</p><p>You can <strong>format text</strong>, add <em>emphasis</em>, and even include images.</p>',
                'views' => 42,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'title' => 'PHP Best Practices',
                'content' => '<h2>Writing Clean PHP Code</h2><p>Here are some essential best practices for writing maintainable PHP code:</p><ul><li>Use proper naming conventions</li><li>Follow PSR standards</li><li>Write tests for your code</li><li>Use type declarations</li></ul><p>These practices will help you write better, more maintainable applications.</p>',
                'views' => 156,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'title' => 'Database Design Tips',
                'content' => '<h2>Designing Efficient Databases</h2><p>Good database design is crucial for application performance. Here are key principles:</p><ol><li>Normalize your data appropriately</li><li>Use proper indexing strategies</li><li>Consider query patterns when designing tables</li><li>Use migrations for version control</li></ol><p>Remember: premature optimization is the root of all evil, but proper planning prevents poor performance.</p>',
                'views' => 89,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'Security in Web Applications',
                'content' => '<h2>Staying Secure</h2><p>Security should be a top priority in any web application. Key areas to focus on:</p><ul><li><strong>Input Validation:</strong> Never trust user input</li><li><strong>SQL Injection:</strong> Use prepared statements</li><li><strong>XSS Prevention:</strong> Sanitize output</li><li><strong>CSRF Protection:</strong> Use tokens for forms</li><li><strong>Authentication:</strong> Implement proper session management</li></ul><p>Remember: security is not a feature, it\'s a requirement!</p>',
                'views' => 203,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
        ];

        $posts = $this->table('posts');
        $posts->insert($data)->saveData();
    }
}
