<div class="row justify-content-center">
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Register</h4>
      </div>
      <div class="card-body">
        <?php if (isset($error)) : ?>
          <div class="alert alert-danger" role="alert">
            <?= e($error) ?>
          </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)) : ?>
          <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
              <?php foreach ($errors as $error) : ?>
                <li><?= e($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="/register" method="POST">
          <?= csrf_token() ?>
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </form>
      </div>
      <div class="card-footer text-center">
        <small class="text-muted">
          Already have an account? <a href="/login">Login here</a>
        </small>
      </div>
    </div>
  </div>
</div>
