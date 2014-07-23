<h2>Public Letters
    <?php
    if (!empty($current_category_id)) {
        $current_category_name = Set::enum($current_category_id, Configure::read('Letter.categories'));
        echo " - ${current_category_name} ";
    }
    ?>

</h2>
<div class="index">
    <table cellspacing=0>
        <tr>
            <th>#</th>
            <th>id</th>
            <th>User</th>
            <th>Category</th>
            <th>Cheer</th>
            <th>Title</th>
            <th>action</th>
        </tr>
        <?php $count = 1; ?>
        <?php foreach ($letters as $letter): ?>
            <tr>
                <td><?= $letter['letter_id'] ?></td>
                <td><?= $letter['id'] ?></td>
                <td><?= $this->Html->link($letter['user_id'], array('action' => 'index', '?' => array('user_id' => $letter['user_id']))) ?></td>
                <td><?
                    if (!empty($letter['category_id'])) {
                        $category_id = $letter['category_id'];
                        $category_name = Set::enum($category_id, Configure::read('Letter.categories'));
                        echo $this->Html->link($category_name, array('action' => 'index', '?' => array('category_id' => $category_id)));
                    } else {
                        echo '-';
                    }
                ?>
                </td>
                <td><?
                    if (!empty($letter['cheer_id'])) {
                        $cheer_id = $letter['cheer_id'];
                        $cheer_name = Set::enum($cheer_id, Configure::read('Letter.cheer_ids'));
                        echo $this->Html->link($cheer_name, array('action' => 'index', '?' => array('cheer_id' => $cheer_id)));
                    } else {
                        echo '-';
                    }
                ?>
                </td>
                <td><?= h($letter['title']) ?></td>
                <td>
                    <?= $this->Html->link('View', array('action' => 'get', $letter['id'])) ?>
                    <?php 
                        if ($letter['user_id'] == $this->Session->read('Auth.User.id')) {
                            echo $this->Form->postLink('Delete',
                                array('action' => 'delete', $letter['id']),
                                array('confirm' => 'Are you sure?'));
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php
    $first_letter_id = $letters[0]['letter_id'];
    $last_letter_id = $letters[count($letters) - 1]['letter_id'];
    $since_id = $first_letter_id + 1;
    $max_id = $last_letter_id - 1;

    // search terms
    foreach(array('cheer_id', 'category_id', 'user_id') as $key) {
        unset($$key);
        $var = "current_$key";
        if (isset($$var)) {
            $$key = $$var;
        }
    }
    $filter = compact('cheer_id', 'category_id', 'user_id');

    $prev_page_url = $this->Html->url(array('?' => $filter + compact('limit', 'since_id'))); 
    $next_page_url = $this->Html->url(array('?' => $filter + compact('limit', 'max_id'))); 
?>

<p>Page 1 of 2, showing 2 records out of 3 total, starting on record 1, ending on 2</p>
<div class="paging">
<span><a href="<?= $prev_page_url ?>">&lt;&lt; previous</a></span> |
<span><a href="<?= $next_page_url ?>">next &gt;&gt;</a></span>
</div>

</div>

<div class="actions">
    <ul>
        <li><?= $this->Html->link('Create', array('controller' => 'my_letter', 'action' => 'create')) ?></li>
    </ul>
</div>


