<div class="container">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <?php if ($this->session->flashdata('info')) : ?>
            <div class="alert alert-info alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?= $this->session->flashdata('info') ?>
            </div>
        <?php endif ?>

        <?= validation_errors(); ?>

        <form action="<?= site_url('connection/first_connection') ?>" method="post" accept-charset="utf-8">
            <input type="hidden" id="email" name="email" value="<?= $email ?>">
            <label for="new_password"><?= $this->lang->line('new_password') ?> : <span class="info"><?= $this->lang->line('password_rules') ?></label>
            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="<?= $this->lang->line('new_password') ?>" required="required">
            <label for="new_password_confirmation"><?= $this->lang->line('new_password_confirmation') ?> : </label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="<?= $this->lang->line('new_password_confirmation') ?>" class="form-control" required="required"><br/>
            <input type="submit" class="btn btn-lg btn-primary btn-block" value="Valider">
        </form>
    </div>
    <div class="col-sm-3 clearfix"></div>
</div>