<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>

<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/admin/dashboard"><?= e(config('app.name')) ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link<?= isActiveNavItem('/') ? ' active' : '' ?>" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= isActiveNavItem('/admin/dashboard') ? ' active' : '' ?>" href="/admin/dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= isActiveNavItem('/admin/posts') ? ' active' : '' ?>" href="/admin/posts">Manage Posts</a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <form action="/logout" method="POST" class="d-inline">
              <?= csrf_token() ?>
              <button type="submit" class="btn btn-outline-light btn-sm">Logout (<?= $user->email ?>)</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container mt-4 flex-grow-1">
    <?= $content ?>
  </main>

  <footer class="bg-dark text-light py-3 mt-5">
    <div class="container">
      <p class="text-center mb-0">&copy; <?= date('Y') ?> <?= e(config('app.name')) ?> - Admin Panel</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <?= asset_tags('assets/js/admin.js') ?>
