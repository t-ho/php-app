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
                'title' => 'PHP 8.3 New Features Overview',
                'sanitized_html_content' => '<h2>What\'s New in PHP 8.3</h2><p>PHP 8.3 introduces several exciting features that make PHP development more efficient:</p><ul><li><strong>Typed class constants:</strong> <code>const string API_VERSION = "v1";</code></li><li><strong>Dynamic class constant access:</strong> <code>$className::API_VERSION</code></li><li><strong>Readonly anonymous classes:</strong> More immutable object patterns</li><li><strong>New randomizer methods:</strong> Better random number generation</li></ul><p>These features continue PHP\'s evolution toward better type safety and developer experience.</p>',
                'views' => 425,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
            ],
            [
                'title' => 'Understanding PHP OOP: Classes and Objects',
                'sanitized_html_content' => '<h2>Object-Oriented Programming in PHP</h2><p>PHP\'s OOP features are powerful tools for creating maintainable applications:</p><pre><code>class User {
    private string $name;
    private string $email;
    
    public function __construct(string $name, string $email) {
        $this->name = $name;
        $this->email = $email;
    }
    
    public function getName(): string {
        return $this->name;
    }
}</code></pre><p>Key OOP concepts: encapsulation, inheritance, polymorphism, and abstraction make code more organized and reusable.</p>',
                'views' => 312,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-28 days')),
            ],
            [
                'title' => 'SQL JOIN Types Explained with Examples',
                'sanitized_html_content' => '<h2>Mastering SQL JOINs</h2><p>Understanding different JOIN types is crucial for database queries:</p><ul><li><strong>INNER JOIN:</strong> Returns matching records from both tables</li><li><strong>LEFT JOIN:</strong> All records from left table, matching from right</li><li><strong>RIGHT JOIN:</strong> All records from right table, matching from left</li><li><strong>FULL OUTER JOIN:</strong> All records when there\'s a match in either table</li></ul><pre><code>SELECT users.name, posts.title
FROM users
LEFT JOIN posts ON users.id = posts.user_id;</code></pre><p>Choose the right JOIN type based on your data requirements.</p>',
                'views' => 567,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
            ],
            [
                'title' => 'PHP Composer: Dependency Management Made Easy',
                'sanitized_html_content' => '<h2>Managing Dependencies with Composer</h2><p>Composer is PHP\'s de facto standard for dependency management:</p><pre><code>{
    "require": {
        "monolog/monolog": "^3.0",
        "guzzlehttp/guzzle": "^7.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}</code></pre><p>Benefits include automatic class loading, version management, and access to thousands of packages via Packagist.</p>',
                'views' => 289,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-22 days')),
            ],
            [
                'title' => 'SQL Indexing Strategies for Performance',
                'sanitized_html_content' => '<h2>Optimizing Database Performance with Indexes</h2><p>Proper indexing can dramatically improve query performance:</p><ul><li><strong>Primary Index:</strong> Automatically created for primary keys</li><li><strong>Unique Index:</strong> Ensures uniqueness and speeds up lookups</li><li><strong>Composite Index:</strong> Multiple columns for complex queries</li><li><strong>Partial Index:</strong> Index only specific rows with WHERE conditions</li></ul><pre><code>CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_post_user_date ON posts(user_id, created_at);</code></pre><p>Remember: indexes speed up SELECT but slow down INSERT/UPDATE operations.</p>',
                'views' => 445,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
            ],
            [
                'title' => 'PHP PSR Standards: Writing Consistent Code',
                'sanitized_html_content' => '<h2>Following PHP Standards Recommendations</h2><p>PSR standards ensure code consistency across PHP projects:</p><ul><li><strong>PSR-1:</strong> Basic coding standard (class names, method names)</li><li><strong>PSR-2/PSR-12:</strong> Coding style guide (indentation, braces)</li><li><strong>PSR-4:</strong> Autoloading standard</li><li><strong>PSR-7:</strong> HTTP message interfaces</li></ul><pre><code>namespace App\\Models;

class UserModel
{
    public function findById(int $id): ?User
    {
        // Method implementation
    }
}</code></pre><p>Following PSR standards makes your code more professional and maintainable.</p>',
                'views' => 198,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
            ],
            [
                'title' => 'Database Normalization: 1NF, 2NF, and 3NF',
                'sanitized_html_content' => '<h2>Understanding Database Normalization</h2><p>Normalization eliminates redundancy and ensures data integrity:</p><ul><li><strong>1NF (First Normal Form):</strong> Eliminate duplicate columns, create separate tables for related data</li><li><strong>2NF (Second Normal Form):</strong> Meet 1NF + eliminate partial dependencies</li><li><strong>3NF (Third Normal Form):</strong> Meet 2NF + eliminate transitive dependencies</li></ul><pre><code>-- Normalized tables
CREATE TABLE users (id, name, email);
CREATE TABLE posts (id, title, content, user_id);
CREATE TABLE categories (id, name);
CREATE TABLE post_categories (post_id, category_id);</code></pre><p>Proper normalization reduces storage and prevents data anomalies.</p>',
                'views' => 378,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            [
                'title' => 'PHP Error Handling: Exceptions and Error Reporting',
                'sanitized_html_content' => '<h2>Robust Error Handling in PHP</h2><p>Proper error handling is crucial for application stability:</p><pre><code>try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    throw new DatabaseException("Connection failed", 0, $e);
}</code></pre><p>Best practices:</p><ul><li>Use try-catch blocks for expected errors</li><li>Log errors for debugging</li><li>Show user-friendly messages</li><li>Set appropriate error reporting levels</li></ul>',
                'views' => 267,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
            ],
            [
                'title' => 'SQL Transactions: ACID Properties and Best Practices',
                'sanitized_html_content' => '<h2>Understanding Database Transactions</h2><p>Transactions ensure data consistency through ACID properties:</p><ul><li><strong>Atomicity:</strong> All operations succeed or all fail</li><li><strong>Consistency:</strong> Database remains in valid state</li><li><strong>Isolation:</strong> Concurrent transactions don\'t interfere</li><li><strong>Durability:</strong> Committed changes persist</li></ul><pre><code>BEGIN TRANSACTION;
    INSERT INTO users (name, email) VALUES (\'John\', \'john@example.com\');
    INSERT INTO profiles (user_id, bio) VALUES (LAST_INSERT_ID(), \'Developer\');
COMMIT;</code></pre><p>Use transactions for related operations that must succeed together.</p>',
                'views' => 412,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            [
                'title' => 'PHP Namespaces: Organizing Your Code',
                'sanitized_html_content' => '<h2>Mastering PHP Namespaces</h2><p>Namespaces prevent naming conflicts and organize code logically:</p><pre><code>namespace App\\Controllers;

use App\\Models\\User;
use App\\Services\\EmailService;

class UserController
{
    public function create(array $data): User
    {
        $user = new User($data);
        $emailService = new EmailService();
        $emailService->sendWelcome($user);
        return $user;
    }
}</code></pre><p>Benefits: avoid naming conflicts, better code organization, cleaner autoloading with PSR-4.</p>',
                'views' => 233,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
            [
                'title' => 'SQL Window Functions: Advanced Analytics',
                'sanitized_html_content' => '<h2>Leveraging SQL Window Functions</h2><p>Window functions perform calculations across related rows:</p><pre><code>SELECT 
    name,
    department,
    salary,
    ROW_NUMBER() OVER (PARTITION BY department ORDER BY salary DESC) as dept_rank,
    AVG(salary) OVER (PARTITION BY department) as dept_avg_salary
FROM employees;</code></pre><p>Common window functions:</p><ul><li><strong>ROW_NUMBER():</strong> Assigns unique numbers</li><li><strong>RANK():</strong> Ranks with gaps for ties</li><li><strong>LAG/LEAD:</strong> Access previous/next row values</li><li><strong>SUM/AVG/COUNT:</strong> Running totals and averages</li></ul>',
                'views' => 356,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
            [
                'title' => 'PHP Traits: Code Reuse Without Inheritance',
                'sanitized_html_content' => '<h2>Understanding PHP Traits</h2><p>Traits provide horizontal code reuse without traditional inheritance:</p><pre><code>trait Timestampable
{
    private DateTime $createdAt;
    private DateTime $updatedAt;
    
    public function touch(): void
    {
        $this->updatedAt = new DateTime();
    }
}

class User
{
    use Timestampable;
    
    // User-specific methods
}</code></pre><p>Traits solve the diamond problem and enable multiple inheritance-like behavior in PHP.</p>',
                'views' => 189,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'title' => 'SQL Query Optimization Techniques',
                'sanitized_html_content' => '<h2>Optimizing SQL Query Performance</h2><p>Techniques to improve query execution time:</p><ul><li><strong>Use EXPLAIN:</strong> Analyze query execution plans</li><li><strong>Limit result sets:</strong> Use LIMIT and WHERE clauses</li><li><strong>Optimize JOINs:</strong> Join on indexed columns</li><li><strong>Avoid SELECT *:</strong> Select only needed columns</li><li><strong>Use EXISTS:</strong> Instead of IN for subqueries</li></ul><pre><code>-- Optimized query
SELECT u.name, COUNT(p.id) as post_count
FROM users u
LEFT JOIN posts p ON u.id = p.user_id
WHERE u.active = 1
GROUP BY u.id, u.name
ORDER BY post_count DESC
LIMIT 10;</code></pre>',
                'views' => 445,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'PHP Design Patterns: Singleton and Factory',
                'sanitized_html_content' => '<h2>Essential Design Patterns in PHP</h2><p>Design patterns provide proven solutions to common problems:</p><h3>Singleton Pattern</h3><pre><code>class DatabaseConnection
{
    private static ?self $instance = null;
    
    private function __construct() {}
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}</code></pre><h3>Factory Pattern</h3><pre><code>class UserFactory
{
    public static function create(string $type): UserInterface
    {
        return match($type) {
            \'admin\' => new AdminUser(),
            \'regular\' => new RegularUser(),
            default => throw new InvalidArgumentException()
        };
    }
}</code></pre>',
                'views' => 298,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'title' => 'Database Migrations: Version Control for Schema',
                'sanitized_html_content' => '<h2>Managing Database Schema Changes</h2><p>Migrations provide version control for your database schema:</p><pre><code>-- Migration: 001_create_users_table.sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Migration: 002_add_email_verified_to_users.sql
ALTER TABLE users 
ADD COLUMN email_verified_at TIMESTAMP NULL;</code></pre><p>Benefits:</p><ul><li>Track schema changes over time</li><li>Rollback capabilities</li><li>Team collaboration</li><li>Deployment consistency</li></ul><p>Tools: Phinx, Laravel Migrations, Doctrine Migrations</p>',
                'views' => 187,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
            ],
            [
                'title' => 'PHP Unit Testing with PHPUnit',
                'sanitized_html_content' => '<h2>Writing Effective Unit Tests</h2><p>PHPUnit is the standard testing framework for PHP:</p><pre><code>class UserTest extends PHPUnit\\Framework\\TestCase
{
    public function testUserCanBeCreated(): void
    {
        $user = new User(\'John Doe\', \'john@example.com\');
        
        $this->assertEquals(\'John Doe\', $user->getName());
        $this->assertEquals(\'john@example.com\', $user->getEmail());
    }
    
    public function testEmailMustBeValid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new User(\'John Doe\', \'invalid-email\');
    }
}</code></pre><p>Testing best practices: test one thing at a time, use descriptive names, follow AAA pattern (Arrange, Act, Assert).</p>',
                'views' => 234,
                'user_id' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
            ],
            [
                'title' => 'SQL Stored Procedures vs Application Logic',
                'sanitized_html_content' => '<h2>When to Use Stored Procedures</h2><p>Stored procedures can improve performance but reduce portability:</p><pre><code>DELIMITER //
CREATE PROCEDURE GetUserPosts(IN user_id INT)
BEGIN
    SELECT p.*, u.name as author_name
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.user_id = user_id
    ORDER BY p.created_at DESC;
END //
DELIMITER ;</code></pre><p><strong>Advantages:</strong></p><ul><li>Better performance for complex operations</li><li>Reduced network traffic</li><li>Centralized business logic</li></ul><p><strong>Disadvantages:</strong></p><ul><li>Database vendor lock-in</li><li>Harder to version control</li><li>Limited debugging tools</li></ul>',
                'views' => 156,
                'user_id' => 2, // John Doe
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
            [
                'title' => 'PHP Security: Preventing SQL Injection',
                'sanitized_html_content' => '<h2>Securing Your PHP Applications</h2><p>SQL injection remains a top security threat. Here\'s how to prevent it:</p><h3>Use Prepared Statements</h3><pre><code>// Wrong - vulnerable to SQL injection
$query = "SELECT * FROM users WHERE email = \'" . $_POST[\'email\'] . "\'";

// Right - using prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$_POST[\'email\']]);
$user = $stmt->fetch();</code></pre><h3>Input Validation</h3><pre><code>function validateEmail(string $email): string
{
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        throw new InvalidArgumentException(\'Invalid email format\');
    }
    return $email;
}</code></pre><p>Never trust user input - always validate and sanitize!</p>',
                'views' => 389,
                'user_id' => 3, // Jane Smith
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            ],
        ];

        $posts = $this->table('posts');
        $posts->insert($data)->saveData();
    }
}
