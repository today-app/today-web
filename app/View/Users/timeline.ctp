<div class="users index">
    <h2>User</h2>

    <p>
        <?php
        if (!empty($is_me)) {
            echo 'It\'s me!';
        } else {
            if ($is_friend) {
                echo 'Friend of me!';
            } elseif ($is_request_sent) {
                echo 'Friendship request sent.';
            } else {
                echo $this->Form->postLink('Send friend request', sprintf('/users/request/%d', $user_id));
            }
        }
        ?>
    </p>

    <div>
        <?php pr($user); ?>
    </div>

    <h2>Friends</h2>

    <div>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th>id</th>
                <th>username</th>
                <?php if ($is_me): ?>
                <th>action</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($user_friends as $user): ?>
                <tr>
                    <td><?php echo $user->id; ?></td>
                    <td><?php echo $user->username; ?></td>
                    <?php if ($is_me): ?>
                    <td class="actions">
                        <?php
                        echo join(
                            ', ',
                            array(
                                $this->Form->postLink('remove', sprintf('/users/remove/%d', $user->id)),
                            )
                        );
                        ?>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php if ($is_me): ?>

        <h2>Incoming</h2>
        <div>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>id</th>
                    <th>username</th>
                    <th>action</th>
                </tr>
                <?php foreach ($incoming as $user): ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo $user->username; ?></td>
                        <td>
                            <?php
                            echo join(
                                ', ',
                                array(
                                    $this->Form->postLink('accept', sprintf('/users/accept/%d', $user->id)),
                                    $this->Form->postLink('reject', sprintf('/users/reject/%d', $user->id)),
                                )
                            );
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <h2>Outgoing</h2>
        <div>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>id</th>
                    <th>username</th>
                    <th>action</th>
                </tr>
                <?php foreach ($outgoing as $user): ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo $user->username; ?></td>
                        <td class="actions">
                            <?php
                            echo join(
                                ', ',
                                array(
                                    $this->Form->postLink('cancel', sprintf('/users/cancel/%d', $user->id)),
                                )
                            );
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

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
