<?php 

$data = Message::getData();
$namaBarang = "";
$jumlah = "";
$harga = "";
$kadaluarsa = "";
if ($data) {
  $namaBarang = $data['nama_barang'];
  $jumlah = $data['jumlah'];
  $harga = $data['harga_satuan'];
  $kadaluarsa = $data['expire_date'];
}

Message::flash()

?>

<div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
    <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
        <h1><a href="#" rel="dofollow">MVC Form</a></h1>
    </div>
    <div class="formbg-outer">
        <div class="formbg">
            <div class="formbg-inner padding-horizontal--48">
                <form action="<?= BASE_URL . '/barang/insert_barang' ?>" id="stripe-login" method="post">
                    <div class="field padding-bottom--24">
                        <label for="nama">Name</label>
                        <input type="text" name="nama_barang" value="<?= $namaBarang ?>">
                    </div>
                    <div class="field padding-bottom--24">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" value="<?= $jumlah ?>">
                    </div>
                    <div class="field padding-bottom--24">
                        <label for="harga_satuan">Harga Satuan</label>
                        <input type="number" name="harga_satuan" value="<?= $harga ?>">
                    </div>
                    <div class="field padding-bottom--24">
                        <label for="expire_date">Kadaluarsa</label>
                        <input type="date" name="expire_date" value="<?= $kadaluarsa ?>"    >
                    </div>
                    <div class="field padding-bottom--24">
                        <input type="submit" name="submit" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>