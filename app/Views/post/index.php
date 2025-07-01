<h1>Welcome to My Blog</h1>
<h2>All Posts</h2>

<form action="" method="GET">
  <input type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
  <button type="submit">Search</button>
</form>

<?= partial('_posts', ['posts' => $posts]) ?>
<?= partial('_pagination', ['currentPage' => $currentPage, 'totalPages' => $totalPages]) ?>
