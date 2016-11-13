<a class="btn btn-sm btn-outline-primary m-b-2" href="<?= site_url('admin/users') ?>"><?= $this->lang->line('back_to_users_admin') ?></a><br/>
<?= validation_errors() ?>

<?php if (!empty($info)) : ?>
    <span><?= $info ?></span>
<?php else : ?>
    <form action="<?= site_url('admin/users/create') ?>" method="post" accept-charset="utf-8">
        <label for="first_name"><?= $this->lang->line('first_name') ?> : </label>
        <input type="text" name="first_name" id="first_name" required="required" value="<?= set_value('first_name') ?>" ><br/>
        <label for="last_name"><?= $this->lang->line('last_name') ?> : </label>
        <input type="text" name="last_name" id="last_name" required="required" value="<?= set_value('last_name') ?>" ><br/>
        <label for="email"><?= $this->lang->line('email') ?> : </label>
        <input type="email" name="email" id="email" required="required" value="<?= set_value('email') ?>" ><br/>
        <label for="password"><?= $this->lang->line('password') ?> : </label>
        <input type="text" name="password" id="password" required="required" value="<?= set_value('password') ?>" ><br/>
        <input type="submit" class="btn btn-sm btn-primary m-t-1" value="<?= $this->lang->line('add') ?>">
    </form>
<?php endif; ?>