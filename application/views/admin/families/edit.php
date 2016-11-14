<a class="btn btn-sm btn-outline-primary m-b-2" href="<?= site_url('admin/families') ?>"><?= $this->lang->line('back_to_families_admin');?></a>

<?= validation_errors(); ?>

<?= form_open('admin/families/edit/'.$family->family_id); ?>
    <label for="family_name"><?= $this->lang->line('family_name') ?> : </label>
    <input type="text" id="family_name" name="family_name" value="<?= $family->name ?>" required="required" autofocus><br/>
    <input type="submit" class="btn btn-sm btn-primary m-t-1" value="<?= $this->lang->line('confirm') ?>">
</form>
