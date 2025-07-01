<h1>Oops! Something went wrong!</h1>

<p><?= e($errorMessage) ?></p>

<?php if ($isDebug) : ?>
    <h2>Stack Trace</h2>
    <pre><?= e($trace) ?></pre>
<?php endif; ?>

<p>
  <a href="/">Return to Homepage</a>
</p>
