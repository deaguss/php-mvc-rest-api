<?php 

use MyApp\Core\Message;

$data = Message::getData();
if ($data) {
  $getBarang['nama_barang'] = $data['nama_barang'];
  $getBarang['jumlah'] = $data['jumlah'];
  $getBarang['harga_satuan'] = $data['harga_satuan'];
  $getBarang['expire_date'] = $data['expire_date'];
}

Message::flash()

?>

<div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
    <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
        <h1>Edit Barang <?= $getBarang['nama_barang'] ?></h1>
    </div>
    <div class="formbg-outer">
        <div class="formbg">
            <div class="formbg-inner padding-horizontal--48">
                <form id="form" action="<?= BASE_URL . '/barang/update_barang' ?>" id="stripe-login" method="post">
                <!-- status -->
                    <input type="hidden" name="id" value="<?= $getBarang['barang_id'] ?>">
                    <input type="hidden" id="mode" name="mode" value="update">

                    <div class="field padding-bottom--24">
                        <label for="nama">Name</label>
                        <input type="text" name="nama_barang" value="<?= $getBarang['nama_barang'] ?>">
                    </div>
                    <div class="field padding-bottom--24">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" value="<?= $getBarang['jumlah'] ?>">
                    </div>
                    <div class="field padding-bottom--24">
                        <label for="harga_satuan">Harga Satuan</label>
                        <input type="number" name="harga_satuan" value="<?= $getBarang['harga_satuan'] ?>">
                    </div>
                    <div class="field padding-bottom--24">
                        <label for="expire_date">Kadaluarsa</label>
                        <input type="date" name="expire_date" value="<?= $getBarang['expire_date'] ?>"    >
                    </div>
                    <div class="field padding-bottom--24">
                        <button onclick="edit('update')" type="button">Edit</button>
                        <button onclick="edit('delete')" type="button">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>