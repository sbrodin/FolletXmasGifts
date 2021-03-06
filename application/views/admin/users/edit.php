<a class="btn btn-sm btn-outline-primary m-b-2" href="<?= site_url('admin/users') ?>"><?= $this->lang->line('back_to_users_admin') ?></a><br/>

<?= validation_errors() ?>

<?php if (!empty($info)) : ?>
    <span><?= $info ?></span>
<?php else : ?>
    <form action="<?= site_url('admin/users/edit/' . $user_id) ?>" method="post" accept-charset="utf-8">
        <label for="first_name"><?= $this->lang->line('first_name') ?> : </label>
        <input type="text" name="first_name" id="first_name" required="required" value="<?= set_value('first_name') ? set_value('first_name') : $user->first_name ?>" ><br/>
        <label for="last_name"><?= $this->lang->line('last_name') ?> : </label>
        <input type="text" name="last_name" id="last_name" required="required" value="<?= set_value('last_name') ? set_value('last_name') : $user->last_name ?>" ><br/>
        <label for="family_id"><?= $this->lang->line('family') ?> : </label>
        <select name="family_id" id="family_id">
            <?php foreach ($families as $family_id => $name) : ?>
                <option value="<?= $family_id ?>" <?= ($user->family_id == $family_id) ? 'selected' : '' ?> ><?= $name ?></option>
            <?php endforeach; ?>
        </select><br/>
        <label for="email"><?= $this->lang->line('email') ?> : </label>
        <input type="email" name="email" id="email" required="required" value="<?= set_value('email') ? set_value('email') : $user->email ?>" ><br/>
        <label for="password"><?= $this->lang->line('password') ?> : </label>
        <input type="text" name="password" id="password" ><br/>
        <input type="submit" class="btn btn-sm btn-primary m-t-1" value="<?= $this->lang->line('confirm') ?>">
    </form>
<?php endif; ?>