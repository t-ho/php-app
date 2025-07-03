<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/custom.css">
</head>

<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="/">My Blog</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/posts">Posts</a>
          </li>
          <?php if ($user && isAuthorizedFor('access_dashboard')) : ?>
            <li class="nav-item">
              <a class="nav-link" href="/admin/dashboard">Admin</a>
            </li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav">
          <?php if ($user) : ?>
            <li class="nav-item">
              <form action="/logout" method="POST" class="d-inline">
                <?= csrf_token() ?>
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout (<?= $user->email ?>)</button>
              </form>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="/login">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/register">Register</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container mt-4 flex-grow-1">
    <?= $content ?>
  </main>

  <footer class="bg-light py-3 mt-5">
    <div class="container">
      <p class="text-center mb-0">&copy; <?= date('Y') ?> My Blog</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
