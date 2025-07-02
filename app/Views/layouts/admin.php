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
    <h1>Dashboard</h1>
  </header>
    <nav>
      <a href="/admin/dashboard">Dashboard</a>
      <a href="/admin/posts">Manage Posts</a>
      <form action="/logout" method="POST" style="display: inline;">
        <?= csrf_token() ?>
        <button type="submit">Logout(<?= $user->email ?>)</button>
      </form>
    </nav>
  <main>
    <?= $content ?>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> My Blog - Admin Panel</p>
  </footer>
