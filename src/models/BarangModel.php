<?php

use MyApp\Core\Database;    


class BarangModel extends Database
{
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('barang');
        $this->setColumn([
            'barang_id', 
            'nama_barang', 
            'jumlah', 
            'harga_satuan', 
            'expire_date'
        ]);
    }

    public function getAll()
    {
        return $this->get()->fetchAll();       
    }

    public function insert($data)
    {
        $table = [
            'nama_barang' => $data['nama_barang'],
            'jumlah' => $data['jumlah'],
            'harga_satuan' => $data['harga_satuan'],
            'expire_date' => $data['expire_date']   
        ];
        return $this->insertData($table);
    }

    public function getById($id){
        return $this->get(['barang_id' => $id])->fetch();   
    }

    public function update($data){
        $table = [
            'nama_barang' => $data['nama_barang'],
            'jumlah' => $data['jumlah'],
            'harga_satuan' => $data['harga_satuan'],
            'expire_date' => $data['expire_date']   
        ];

        $key = [
            'barang_id' => $data['id']
        ];
        return $this->updateData($table, $key); 
    }

    public function delete($id){
        return $this->deleteData(['barang_id' => $id]);
    }
}
