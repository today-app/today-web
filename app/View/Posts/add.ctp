<div class="posts form">
<h2>Add Post</h2>

<?php
echo $this->Form->create('Post');
echo $this->Form->input('text');
echo $this->Form->submit();
?>
</div>

<div class="actions">
<ul>
<li><?= $this->Html->link('Posts', '/posts/index') ?></li>
</ul>
</div>
