<header class="header clearfix">
    <?php if (is_connected()) : ?>
        <a class="btn btn-primary m-b-2 m-r-1" href="<?= site_url() ?>"><?= $this->lang->line('home') ?></a>
        <?php if (user_can('admin_all')) : ?>
            <a class="btn btn-primary m-b-2 m-r-1" href="<?= site_url('admin') ?>"><?= $this->lang->line('site_admin') ?></a>
        <?php endif; ?>
        <a class="btn btn-outline-primary m-b-2" href="<?= site_url('connection/logout') ?>"><?= $this->lang->line('log_out') ?></a>
    <?php endif; ?>
</header>
<div class="main-container">