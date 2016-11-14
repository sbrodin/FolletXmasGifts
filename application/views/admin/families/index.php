<a class="btn btn-sm btn-outline-primary m-b-2" href="<?= site_url('admin/families/add') ?>"><?= $this->lang->line('add_a_family');?></a>
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif ?>
<table>
    <thead>
        <tr>
            <th><?= $this->lang->line('family_name') ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($families as $key => $family) : ?>
        <tr>
            <td><?= $family->name ?></td>
            <td>
                <a href="<?= site_url('admin/families/edit/'.$family->family_id) ?>"><?= $this->lang->line('edit') ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>