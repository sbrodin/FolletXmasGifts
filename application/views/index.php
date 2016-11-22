<?php if ($this->session->flashdata('warning')) : ?>
    <div class="alert alert-warning alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?= $this->session->flashdata('warning') ?>
    </div>
<?php endif ?>

<?= $this->lang->line('no_draw_yet') ?>