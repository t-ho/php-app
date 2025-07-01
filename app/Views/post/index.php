<h2>All Posts</h2>

<form action="" method="GET">
  <input type="text" name="search" placeholder="Search posts..." value="<?= e($search) ?>">
  <button type="submit">Search</button>
</form>

<?= partial('_posts', ['posts' => $posts]) ?>
<?= partial('_pagination', ['currentPage' => $currentPage, 'totalPages' => $totalPages]) ?>
