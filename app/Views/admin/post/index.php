<h2>Manage posts</h2>

<a href="/admin/posts/create">Create New Post</a>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
            <tr>
                <td><?= e($post->title) ?></td>
                <td><?= e(substr($post->content, 0, 50)) ?>...</td>
                <td><?= e($post->created_at) ?></td>
                <td>
                    <a href="/admin/posts/<?= $post->id ?>/edit">Edit</a>
                    <form action="/admin/posts/<?= $post->id ?>/delete" method="POST" style="display:inline;">
                        <?= csrf_token() ?>
                        <button type="submit" onclick="return confirm('Are you sure to delete this?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= partial('_pagination', ['currentPage' => $currentPage, 'totalPages' => $totalPages]) ?>
