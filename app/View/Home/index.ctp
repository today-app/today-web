<div class="posts index">
<h2>Home</h2>
<ul>
<?php foreach($posts as $post): ?>
<li>
    <div>[<?= $post->id ?>] <?= $this->Html->link($post->text, sprintf('/posts/view/%d', $post->id)) ?></div>
    <div><?php
        if (isset($post->user->username)) {
            $username = $post->user->username;
            echo $this->Html->link($username, sprintf('/users/%s', $username));
        } else {
            echo '&lt;unknown user&gt;';
        }
        ?>
    </div>
    <?php pr($post); ?>
</li>
<?php endforeach; ?>
</ul>
</div>

<div class="actions">
<ul>
<li><?= $this->Html->link('Add Post', '/posts/add') ?></li>
</ul>
</div>
