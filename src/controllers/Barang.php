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

    public function edit($id)
    {
        echo "Edit from barang {$id}";
    }
}
