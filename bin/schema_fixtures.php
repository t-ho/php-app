<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Core\App;

$users = [
  [
    'name' => 'Admin User',
    'email' => 'admin@tdev.app',
    'password' => password_hash('password', PASSWORD_DEFAULT),
    'role' => 'admin',
  ],
  [
    'name' => 'John Doe',
    'email' => 'john@tdev.app',
    'password' => password_hash('password', PASSWORD_DEFAULT),
    'role' => 'user',
  ],
  [
    'name' => 'Lena Hartley',
    'email' => 'lena@tdev.app',
    'password' => password_hash('password', PASSWORD_DEFAULT),
    'role' => 'user',
  ]
];

$posts = [
  [
    'user_id' => 1,
    'title' => 'Welcome to Our Blog',
    'content' => 'This is our first blog post. We hope you enjoy it!'
  ],
  [
    'user_id' => 2,
    'title' => 'PHP Tips and Tricks',
    'content' => 'Here are some useful PHP tips and tricks for beginners...'
  ],
  [
    'user_id' => 3,
    'title' => 'Web Development Best Practices',
    'content' => 'In this post, we\'ll discuss some web development best practices...'
  ],
  [
    'user_id' => 2,
    'title' => 'Understanding REST APIs',
    'content' => 'REST APIs are an essential part of modern web development. Let\'s explore how they work.'
  ],
  [
    'user_id' => 1,
    'title' => 'Getting Started with SQLite',
    'content' => 'SQLite is a great lightweight database for small applications. Here\'s how to use it.'
  ],
  [
    'user_id' => 3,
    'title' => 'Deploying PHP Apps with Docker',
    'content' => 'Learn how to containerize your PHP application using Docker for easy deployment.'
  ],
];


$comments = [
  [
    'post_id' => 1,
    'user_id' => 2,
    'content' => 'Great first post! Looking forward to more.'
  ],
  [
    'post_id' => 1,
    'user_id' => 3,
    'content' => 'Welcome to the blogosphere!'
  ],
  [
    'post_id' => 2,
    'user_id' => 1,
    'content' => 'These are some really useful tips, thanks!'
  ],
  [
    'post_id' => 3,
    'user_id' => 2,
    'content' => 'I\'ve been using these practices and they really help.'
  ],
  [
    'post_id' => 4,
    'user_id' => 1,
    'content' => 'This made REST APIs much clearer for me, thank you.'
  ],
  [
    'post_id' => 5,
    'user_id' => 3,
    'content' => 'SQLite is so simple and effective. Great write-up!'
  ],
  [
    'post_id' => 6,
    'user_id' => 1,
    'content' => 'Dockerizing PHP was intimidating, but this helped a lot.'
  ],
];


$db = App::get('database');

$db->query("DELETE FROM comments");
$db->query("DELETE FROM posts");
$db->query("DELETE FROM users");

$db->query("DELETE FROM sqlite_sequence WHERE name IN ('users', 'posts', 'comments')");

foreach ($users as $user) {
    User::create($user);
}

foreach ($posts as $post) {
    Post::create($post);
}

foreach ($comments as $comment) {
    Comment::create($comment);
}

echo "Database fixtures created successfully." . PHP_EOL;
