<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CommentSeeder extends AbstractSeed
{
    /**
     * Specify seeder dependencies (Users and Posts must exist first)
     */
    public function getDependencies(): array
    {
        return [
            'UserSeeder',
            'PostSeeder'
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
                'post_id' => 1,
                'user_id' => 2, // John Doe
                'content' => 'Great post! This really helped me understand the concepts better. Thanks for sharing your knowledge.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
            [
                'post_id' => 1,
                'user_id' => 3, // Jane Smith
                'content' => 'Welcome to the community! Looking forward to reading more posts like this.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
            [
                'post_id' => 2,
                'user_id' => 1, // Admin
                'content' => 'Excellent tips! I especially appreciate the section about PSR standards. Keep up the good work.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'post_id' => 2,
                'user_id' => 3, // Jane Smith
                'content' => 'These practices have really improved my code quality. The type declarations tip was particularly useful.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'post_id' => 3,
                'user_id' => 1, // Admin
                'content' => 'Solid advice on database design. The point about query patterns is often overlooked.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'post_id' => 3,
                'user_id' => 2, // John Doe
                'content' => 'I\'ve been struggling with database optimization. This gives me a good starting point. Thank you!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'post_id' => 4,
                'user_id' => 2, // John Doe
                'content' => 'Security is so important but often an afterthought. Thanks for emphasizing these key points.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
            ],
            [
                'post_id' => 4,
                'user_id' => 3, // Jane Smith
                'content' => 'The CSRF protection section was really helpful. I need to implement this in my current project.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 hours')),
            ],
            [
                'post_id' => 1,
                'user_id' => 1, // Admin
                'content' => 'Thanks for the positive feedback everyone! More tutorials coming soon.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'post_id' => 2,
                'user_id' => 2, // John Doe
                'content' => 'Quick question: do you have any recommendations for PHP testing frameworks?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            ],
        ];

        $comments = $this->table('comments');
        $comments->insert($data)->saveData();
    }
}
