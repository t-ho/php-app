<div class="row justify-content-center">
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Login</h4>
      </div>
      <div class="card-body">
        <?php if (isset($error)) : ?>
          <div class="alert alert-danger" role="alert">
            <?= e($error) ?>
          </div>
        <?php endif; ?>

        <form action="/login" method="POST">
          <?= csrf_token() ?>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
      <div class="card-footer text-center">
        <small class="text-muted">
          Don't have an account? <a href="/register">Register here</a>
        </small>
      </div>
    </div>
  </div>
</div>
