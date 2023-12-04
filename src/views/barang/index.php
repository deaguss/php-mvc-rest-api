<?php
    Message::flash();
?>

<div class="container">
    <div class="header">
        <h2>Data Barang</h2>
    </div>
    <div class="row">
        <div>
            <button onclick="location.href='<?= BASE_URL . '/barang/insert' ?>'" class="btn primary">Tambah</button>
        </div>
        <table id="example" class="stripe" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Kadaluarsa</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $no = 1;
                foreach ($getAllBarang as $row) :
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_barang'] ?></td>
                        <td><?= $row['jumlah'] ?></td>
                        <td><?= $row['harga_satuan'] ?></td>
                        <td><?= $row['expire_date'] ?></td>
                        <td>
                            <a href="<?= BASE_URL . '/barang/edit/' . $row['barang_id'] ?>">Edit</a>
                            <a>Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
        </table>
    </div>
</div>