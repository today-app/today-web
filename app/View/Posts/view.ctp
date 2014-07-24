<div class="posts index">
<h2>Post Detail</h2>

<?php pr($post); ?>
</div>

<div class="actions">
<ul>
<li><?= $this->Html->link('Posts', '/posts/index') ?></li>
<li><?= $this->Html->link('Add Post', '/posts/add') ?></li>
</ul>
</div>
