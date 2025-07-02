<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <style rel="stylesheet" href="/style.css"></style>
</head>

<body>
  <header>
    <h1>My Blog</h1>
  </header>
    <nav>
      <a href="/">Home</a>
      <a href="/posts">Posts</a>
      <?php if ($user) : ?>
        <a href="/admin/dashboard">Admin</a>
        <form action="/logout" method="POST" style="display: inline;">
          <?= csrf_token() ?>
          <button type="submit">Logout(<?= $user->email ?>)</button>
        </form>
      <?php else : ?>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
      <?php endif; ?>
    </nav>
  <main>
    <?= $content ?>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> My Blog</p>
  </footer>
