<a class="btn btn-sm btn-outline-primary m-b-1" href="<?= site_url('admin/offersto/add') ?>"><?= $this->lang->line('add_a_link');?></a>

<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif ?>

<form action="<?= site_url('admin/offersto') ?>" method="post" accept-charset="utf-8" class="m-t-1 m-b-1">
    <label for="current_year"><?= $this->lang->line('change_current_year') ?> : </label>
    <select name="current_year" id="current_year">
        <?php foreach ($years as $year) : ?>
            <option value="<?= $year ?>" <?= ($year == $current_year) ? 'selected' : '' ?> ><?= $year ?></option>
        <?php endforeach; ?>
    </select>
</form>

<table>
    <thead>
        <tr>
            <th><?= $this->lang->line('sender') ?></th>
            <th>-></th>
            <th><?= $this->lang->line('receiver') ?></th>
            <th><?= $this->lang->line('year') ?></th>
            <!-- <th></th> -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($links as $key => $link) : ?>
        <tr>
            <td><?= $link->sender_fn . ' ' . $link->sender_ln ?></td>
            <td>-></td>
            <td><?= $link->receiver_fn . ' ' . $link->receiver_ln ?></td>
            <td><?= $link->year ?></td>
            <!-- <td>
                <a href="<?= site_url('admin/offersto/edit/'.$link->link_id) ?>"><?= $this->lang->line('edit') ?></a>
            </td> -->
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>