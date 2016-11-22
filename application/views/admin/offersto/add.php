<a class="btn btn-sm btn-outline-primary m-b-2" href="<?= site_url('admin/offersto') ?>"><?= $this->lang->line('back_to_offersto_admin');?></a>

<?php if (!empty($error_already_exists)) : ?>
    <div class="m-b-2"><?= $error_already_exists ?></div>
<?php endif; ?>

<?php if (!empty($error_same_user)) : ?>
    <div class="m-b-2"><?= $error_same_user ?></div>
<?php endif; ?>

<?= validation_errors() ?>

<form action="<?= site_url('admin/offersto/add') ?>" method="post" accept-charset="utf-8">
    <label for="sender"><?= $this->lang->line('sender') ?> : </label>
    <select name="sender" id="sender">
        <?php foreach ($users as $user_id => $name) : ?>
            <option value="<?= $user_id ?>" <?= (set_value('sender') == $user_id) ? 'selected' : '' ?> ><?= $name ?></option>
        <?php endforeach; ?>
    </select><br/>
    <label for="receiver"><?= $this->lang->line('receiver') ?> : </label>
    <select name="receiver" id="receiver">
        <?php foreach ($users as $user_id => $name) : ?>
            <option value="<?= $user_id ?>" <?= (set_value('receiver') == $user_id) ? 'selected' : '' ?> ><?= $name ?></option>
        <?php endforeach; ?>
    </select><br/>
    <label for="year"><?= $this->lang->line('year') ?> : </label>
    <input type="number" id="year" name="year" value="<?= set_value('year')?>" required="required" min="2016"><br/>
    <input type="submit" class="btn btn-sm btn-primary m-t-1" value="<?= $this->lang->line('add') ?>">
</form>
