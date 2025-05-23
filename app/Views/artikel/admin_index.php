<?= $this->include('templates/header'); ?>
<form method="get" class="form-search">
  <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari Data">
  <input type="submit" value="Cari" class="btn btn-primary">
</form>

<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Judul</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($artikel): foreach ($artikel as $row): ?>
        <tr>
          <td><?= $row['id']; ?></td>
          <td>
            <b href="<?= base_url('artikel/detail') ?>"><?= $row['judul']; ?></b>
            <p><small><?= substr($row['isi'], 0, 50); ?></small></p>
          </td>
          <td><?= $row['status']; ?></td>
          <td>
            <a class="btn" href="<?= base_url('admin/artikel/edit/' . $row['id']); ?>">Ubah</a>

            <a class="btn" href="<?= base_url('admin/artikel/delete/' . $row['id']); ?>">Hapus</a>
          </td>
        </tr>
      <?php endforeach;
    else: ?>
      <tr>
        <td colspan="4">Belum ada Data.</td>
      </tr>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <th>ID</th>
      <th>Judul</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </tfoot>
</table>

<?= $pager->links(); ?>
<?= $this->include('templates/footer'); ?>
