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
            // Comments on PHP 8.3 New Features (post_id: 1)
            [
                'post_id' => 1,
                'user_id' => 2, // John Doe
                'content' => 'Finally! Typed class constants were long overdue. This will make my APIs so much cleaner.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-29 days')),
            ],
            [
                'post_id' => 1,
                'user_id' => 3, // Jane Smith
                'content' => 'The readonly anonymous classes feature is interesting. Can you show an example of where this would be useful?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-29 days')),
            ],
            [
                'post_id' => 1,
                'user_id' => 1, // Admin
                'content' => '@Jane Great question! I\'ll write a follow-up post with practical examples. Stay tuned!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-28 days')),
            ],
            
            // Comments on PHP OOP (post_id: 2)
            [
                'post_id' => 2,
                'user_id' => 1, // Admin
                'content' => 'Excellent explanation of encapsulation! The constructor property promotion syntax is a game-changer.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-27 days')),
            ],
            [
                'post_id' => 2,
                'user_id' => 3, // Jane Smith
                'content' => 'I\'ve been using procedural PHP for years. This makes me want to dive deeper into OOP patterns.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-27 days')),
            ],
            [
                'post_id' => 2,
                'user_id' => 2, // John Doe
                'content' => 'The type declarations really help with IDE autocomplete. Makes development so much faster!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-26 days')),
            ],
            
            // Comments on SQL JOINs (post_id: 3)
            [
                'post_id' => 3,
                'user_id' => 1, // Admin
                'content' => 'LEFT JOIN is probably the most commonly used after INNER JOIN. Great examples here!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-24 days')),
            ],
            [
                'post_id' => 3,
                'user_id' => 2, // John Doe
                'content' => 'I always got confused between LEFT and RIGHT JOIN. This explanation finally made it click!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-24 days')),
            ],
            [
                'post_id' => 3,
                'user_id' => 3, // Jane Smith
                'content' => 'The visual examples really help. Would love to see performance comparisons between different JOIN types.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-23 days')),
            ],
            
            // Comments on Composer (post_id: 4)
            [
                'post_id' => 4,
                'user_id' => 2, // John Doe
                'content' => 'Composer has revolutionized PHP development. Remember the days of manual includes? *shudders*',
                'created_at' => date('Y-m-d H:i:s', strtotime('-21 days')),
            ],
            [
                'post_id' => 4,
                'user_id' => 3, // Jane Smith
                'content' => 'The PSR-4 autoloading section is gold. No more require_once hell!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-21 days')),
            ],
            [
                'post_id' => 4,
                'user_id' => 1, // Admin
                'content' => 'Don\'t forget about composer.lock! Always commit it to ensure consistent dependencies across environments.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
            ],
            
            // Comments on SQL Indexing (post_id: 5)
            [
                'post_id' => 5,
                'user_id' => 3, // Jane Smith
                'content' => 'Great point about indexes slowing down writes. It\'s all about finding the right balance.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-19 days')),
            ],
            [
                'post_id' => 5,
                'user_id' => 1, // Admin
                'content' => 'Pro tip: Use EXPLAIN to analyze your queries and see if indexes are being used effectively.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-19 days')),
            ],
            [
                'post_id' => 5,
                'user_id' => 2, // John Doe
                'content' => 'Composite indexes saved my bacon on a large dataset. Query time went from 30s to 0.1s!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
            ],
            
            // Comments on PSR Standards (post_id: 6)
            [
                'post_id' => 6,
                'user_id' => 1, // Admin
                'content' => 'PSR-12 is the updated coding style standard. Make sure you\'re following the latest version!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-17 days')),
            ],
            [
                'post_id' => 6,
                'user_id' => 2, // John Doe
                'content' => 'Our team started enforcing PSR standards with PHP-CS-Fixer. Code reviews are much smoother now.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-17 days')),
            ],
            [
                'post_id' => 6,
                'user_id' => 3, // Jane Smith
                'content' => 'The consistency really helps when jumping between different projects. Everyone\'s code looks familiar.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-16 days')),
            ],
            
            // Comments on Database Normalization (post_id: 7)
            [
                'post_id' => 7,
                'user_id' => 2, // John Doe
                'content' => 'Sometimes you need to denormalize for performance. The key is knowing when to break the rules.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
            ],
            [
                'post_id' => 7,
                'user_id' => 3, // Jane Smith
                'content' => 'The examples make it so clear! I finally understand why we separate concerns in database design.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
            ],
            [
                'post_id' => 7,
                'user_id' => 1, // Admin
                'content' => 'BCNF (Boyce-Codd Normal Form) is worth learning too, though 3NF covers most practical cases.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-13 days')),
            ],
            
            // Comments on PHP Error Handling (post_id: 8)
            [
                'post_id' => 8,
                'user_id' => 3, // Jane Smith
                'content' => 'Custom exception classes are a game-changer for debugging. Much better than generic exceptions.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
            ],
            [
                'post_id' => 8,
                'user_id' => 1, // Admin
                'content' => 'Don\'t forget about finally blocks! They\'re perfect for cleanup operations.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
            ],
            [
                'post_id' => 8,
                'user_id' => 2, // John Doe
                'content' => 'The error_log tip is crucial. Never echo errors to users in production!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            
            // Comments on SQL Transactions (post_id: 9)
            [
                'post_id' => 9,
                'user_id' => 1, // Admin
                'content' => 'Isolation levels are another important aspect. SERIALIZABLE vs READ_COMMITTED can make a huge difference.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
            ],
            [
                'post_id' => 9,
                'user_id' => 2, // John Doe
                'content' => 'I learned this the hard way when I had partial data corruption. Always use transactions for related operations!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
            ],
            [
                'post_id' => 9,
                'user_id' => 3, // Jane Smith
                'content' => 'The ACID acronym is so helpful for remembering the key properties. Thanks for the clear explanation!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
            
            // Comments on PHP Namespaces (post_id: 10)
            [
                'post_id' => 10,
                'user_id' => 2, // John Doe
                'content' => 'The use statement examples are perfect. I always wondered about aliasing with \'as\' keyword.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'post_id' => 10,
                'user_id' => 3, // Jane Smith
                'content' => 'Namespaces paired with Composer autoloading is pure magic. No more manual includes!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            
            // Comments on SQL Window Functions (post_id: 11)
            [
                'post_id' => 11,
                'user_id' => 1, // Admin
                'content' => 'Window functions are incredibly powerful but often overlooked. Great intro to the topic!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'post_id' => 11,
                'user_id' => 3, // Jane Smith
                'content' => 'The PARTITION BY clause explanation really clarifies how these work. Much clearer than subqueries!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'post_id' => 11,
                'user_id' => 2, // John Doe
                'content' => 'ROW_NUMBER() saved me so much time for pagination queries. No more LIMIT OFFSET issues!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            
            // Comments on PHP Traits (post_id: 12)
            [
                'post_id' => 12,
                'user_id' => 1, // Admin
                'content' => 'Traits are great for cross-cutting concerns like logging, caching, and timestamps.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'post_id' => 12,
                'user_id' => 2, // John Doe
                'content' => 'The diamond problem explanation is spot-on. Traits solve multiple inheritance issues elegantly.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            
            // Comments on SQL Query Optimization (post_id: 13)
            [
                'post_id' => 13,
                'user_id' => 3, // Jane Smith
                'content' => 'The EXPLAIN tip is invaluable. It\'s like having X-ray vision for your queries!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'post_id' => 13,
                'user_id' => 2, // John Doe
                'content' => 'SELECT * is the devil! I\'ve seen it bring down entire applications when tables grow large.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'post_id' => 13,
                'user_id' => 1, // Admin
                'content' => 'Query plan caching is another optimization technique worth exploring for frequently executed queries.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 hours')),
            ],
            
            // Comments on PHP Design Patterns (post_id: 14)
            [
                'post_id' => 14,
                'user_id' => 3, // Jane Smith
                'content' => 'Design patterns are like having a vocabulary for common solutions. The Factory pattern is my favorite!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 hours')),
            ],
            [
                'post_id' => 14,
                'user_id' => 2, // John Doe
                'content' => 'Singleton is controversial but useful for things like database connections. Just don\'t overuse it!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-16 hours')),
            ],
            [
                'post_id' => 14,
                'user_id' => 1, // Admin
                'content' => 'Observer pattern and Strategy pattern are also worth learning. They\'re everywhere in modern frameworks.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 hours')),
            ],
            
            // Comments on Database Migrations (post_id: 15)
            [
                'post_id' => 15,
                'user_id' => 2, // John Doe
                'content' => 'Migrations are a lifesaver for team development. No more "it works on my machine" database issues!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 hours')),
            ],
            [
                'post_id' => 15,
                'user_id' => 1, // Admin
                'content' => 'Always test your rollback migrations! I\'ve been burned by broken down() methods in production.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 hours')),
            ],
            
            // Comments on PHP Unit Testing (post_id: 16)
            [
                'post_id' => 16,
                'user_id' => 3, // Jane Smith
                'content' => 'The AAA pattern makes tests so much more readable. Arrange, Act, Assert - simple but effective!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
            ],
            [
                'post_id' => 16,
                'user_id' => 2, // John Doe
                'content' => 'Data providers in PHPUnit are amazing for testing edge cases. One test, multiple scenarios!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
            
            // Comments on SQL Stored Procedures (post_id: 17)
            [
                'post_id' => 17,
                'user_id' => 1, // Admin
                'content' => 'The portability vs performance trade-off is real. Choose based on your specific requirements.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'post_id' => 17,
                'user_id' => 3, // Jane Smith
                'content' => 'User-defined functions are a nice middle ground between stored procedures and application logic.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-90 minutes')),
            ],
            
            // Comments on PHP Security (post_id: 18)
            [
                'post_id' => 18,
                'user_id' => 2, // John Doe
                'content' => 'SQL injection is still #1 on OWASP Top 10. Prepared statements should be mandatory!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
            ],
            [
                'post_id' => 18,
                'user_id' => 1, // Admin
                'content' => 'Don\'t forget about XSS prevention too! htmlspecialchars() and CSP headers are your friends.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
            ],
            [
                'post_id' => 18,
                'user_id' => 3, // Jane Smith
                'content' => 'The filter_var() validation functions are underrated. They handle so many edge cases automatically.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
            ],
        ];

        $comments = $this->table('comments');
        $comments->insert($data)->saveData();
    }
}
