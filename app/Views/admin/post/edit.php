<h2>Update Post</h2>

<form action="/admin/posts/<?= $post->id ?>" method="POST">
    <?= csrf_token() ?>
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= e($post->title) ?>" required>
    </div>
    <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required><?= e($post->content) ?></textarea>
    </div>
    <button type="submit">Update Post</button>
</form>
