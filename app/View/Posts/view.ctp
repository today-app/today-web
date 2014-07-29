<div class="posts index">
<h2>Post Detail</h2>

<?php pr($post); ?>

<h3>Comments</h3>
    
<?php pr($comments); ?>

<h3>Add Comment</h3>
<?php
echo $this->Form->create('Comment', array('url' => '/posts/comment_add'));
echo $this->Form->input('post_id', array('type' => 'hidden', 'value' => $post->id));
echo $this->Form->input('text');
echo $this->Form->submit('Add!');
?>

</div>

<div class="actions">
<ul>
<li><?= $this->Html->link('Posts', '/posts/index') ?></li>
<li><?= $this->Html->link('Add Post', '/posts/add') ?></li>
</ul>
</div>
