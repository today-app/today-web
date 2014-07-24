<div class="posts index">
<h2>Posts</h2>
<ul>
<?php foreach($posts as $post): ?>
<li>[<?= $post->id ?>] <?= $this->Html->link($post->text, sprintf('/posts/view/%d', $post->id)) ?></li>
<?php endforeach; ?>
</ul>
</div>

<div class="actions">
<ul>
<li><?= $this->Html->link('Add Post', '/posts/add') ?></li>
</ul>
</div>
