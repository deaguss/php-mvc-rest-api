<?php

class Barang extends BaseController
{

    private $barangModel;

    public function __construct()
    {
        $this->barangModel = $this->model('BarangModel');
    }

    public function index()
    {
        $data = [
            'title' => 'Barang',
            'getAllBarang' => $this->barangModel->getAll()
        ];
        $this->view('template/header', $data);
        $this->view('template/sidebar', $data);
        $this->view('barang/index', $data);
        $this->view('template/js');
    }

    public function insert()
    {
        $data = [
            'title' => 'Barang'
        ];

        $this->view('template/header', $data);
        $this->view('template/sidebar', $data);
        $this->view('barang/insert', $data);
        $this->view('template/js');
    }

    public function insert_barang()
    {
        $fields =  [
            'nama_barang' => 'string | required',
            'jumlah' =>  'int | required',
            'harga_satuan' => 'float | required',
            'expire_date' => 'string'
        ];

        $messages = [
            'nama_barang' => [
                'required' => 'Nama barang harus diisi',
                'alphanumeric' => 'Masukan Huruf dan angka saja',
            ],
            'jumlah' => [
                'required' => 'Jumlah barang harus diisi',
            ],
            'harga_satuan' => [
                'required' => 'Harga barang harus diisi',
            ]
        ];
        [$inputs, $errors] = $this->filter($_POST, $fields, $messages);
        if ($inputs['expire_date'] == "") {
            $inputs['expire_date'] = "0000-00-00";
        }

        if ($errors) {
            Message::setFlash('error', 'Gagal !', $errors[0], $inputs);
            $this->redirect('barang/insert');
          }
      
        $proc = $this->barangModel->insert($inputs);
        if ($proc) {
            Message::setFlash('success', 'Berhasil !', 'Data Berhasil Disimpan');
            $this->redirect('barang');
        }
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Barang',
            'getBarang' => $this->barangModel->getById($id)
        ];
        $this->view('template/header', $data);
        $this->view('template/sidebar', $data);
        $this->view('barang/edit', $data);
        $this->view('template/js');
    }


    public function update_barang(){
        $fields = [
            'nama_barang' => 'string | required',
            'jumlah' => 'int | required',
            'harga_satuan' => 'float | required',
            'expire_date' => 'string',
            'mode' => 'string',
            'id' => 'int'
          ];
      
          $messages = [
            'nama_barang' => [
              'required' => 'Nama barang harus diisi',
              'alphanumeric' => 'Masukan Huruf dan angka saja',
            ],
            'jumlah' => [
              'required' => 'Jumlah barang harus diisi',
            ],
            'harga_satuan' => [
              'required' => 'Harga barang harus diisi',
            ]
          ];
          [$inputs, $errors] = $this->filter($_POST, $fields, $messages);
          if ($inputs['kadaluarsa'] == "") {
            $inputs['kadaluarsa'] = "0000-00-00";
          }
      
          if ($errors) {
            Message::setFlash('error', 'Gagal !', $errors[0], $inputs);
            $this->redirect('barang/edit/' . $inputs['id']);
          }
      
          if ($inputs['mode'] == "update") {
            $updated = $this->barangModel->update($inputs);
            if ($updated) {
              Message::setFlash('success', 'Berhasil !', 'Data Berhasil Diubah');
              $this->redirect('barang');
            }
          } else if ($inputs['mode'] == "delete") {
            $deleted = $this->barangModel->delete($inputs['id']);
            if ($deleted) {
              Message::setFlash('success', 'Berhasil !', 'Data Berhasil Dihapus');
              $this->redirect('barang');
            }
          }
    }
}
