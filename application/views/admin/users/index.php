<a class="btn btn-sm btn-outline-primary m-b-2" href="<?= site_url('admin/users/create') ?>"><?= $this->lang->line('add_a_user');?></a>
<table class="table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th></th>
            <th><?= $this->lang->line('first_name') ?></th>
            <th><?= $this->lang->line('last_name') ?></th>
            <th><?= $this->lang->line('email') ?></th>
            <th><?= $this->lang->line('last_connection') ?></th>
            <th><?= $this->lang->line('acl') ?></th>
            <th><?= $this->lang->line('is_active') ?></th>
            <th><?= $this->lang->line('activate_deactivate') ?></th>
            <th><?= $this->lang->line('edit') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $num => $user) : ?>
        <tr>
            <td><?= $user->user_id ?></td>
            <td><?= $user->first_name ?></td>
            <td><?= $user->last_name ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->last_connection_formatted ?></td>
            <td><?= $user->acl ?></td>
            <td><?= $user->active ?></td>
            <td>
                <?php if ($user->user_id !== $this->session->userdata['user']->user_id && $user->acl !== 'admin') : ?>
                    <?php if ($user->active === $this->lang->line('yes')) : ?>
                        <a href="<?= site_url('admin/users/deactivate/'.$user->user_id) ?>"><?= $this->lang->line('deactivate_user') ?></a>
                    <?php else : ?>
                        <a href="<?= site_url('admin/users/activate/'.$user->user_id) ?>"><?= $this->lang->line('activate_user') ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= site_url('admin/users/edit/'.$user->user_id) ?>"><?= $this->lang->line('edit') ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>