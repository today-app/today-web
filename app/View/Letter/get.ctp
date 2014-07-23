<h2>Public Letter Detail</h2>
<div class="index">
    <?php pr($letter); ?>

    <p>&nbsp;</p>

    <h3>Write new comment.</h3>
    <div><a href="#" id="fill-fake-data">Fill fake data.</a></div>
    <?php
    echo $this->Form->create('Letter', array('url' => array('controller' => 'letter', 'action' => 'comment_add')));
    echo $this->Form->input('letter_id', array('type' => 'hidden', 'value' => $letter_id));
    echo $this->Form->input('content', array('type' => 'text'));
    echo $this->Form->submit();
    echo $this->Form->end();
    ?>

    <hr/>
    <table cellspacing="0">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Content</th>
            <th>Action</th>
        </tr>
        <?php foreach ($comments['comments'] as $comment): ?>
            <tr>
                <td><?= $comment['id'] ?></td>
                <td><?= $comment['user_id'] ?></td>
                <td><?= h($comment['content']) ?></td>
                <td>
                    <?php echo $this->Form->postLink('Delete',
                        array('action' => 'comment_delete', $letter_id, $comment['id']),
                        array('confirm' => 'Are you sure?'));
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

<div class="actions">
    <ul>
        <li><?= $this->Html->link('Index', array('action' => 'index')) ?></li>
    </ul>
</div>

<script>
$(document).ready(function() {

    $('#fill-fake-data').click(function() {
        $.each(["LetterContent"], function(i, v) {
            $('#' + v).val(Faker.Lorem.sentence());
        });

        return false;
    });

});
</script>
