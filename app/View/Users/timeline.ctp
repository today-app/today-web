<div class="users index">
    <h2>User</h2>
    <div>
        <?php pr($user); ?>
    </div>
    <h2>Timeline</h2>
    <div>
        <?php pr($posts); ?>
    </div>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
    </ul>
</div>
