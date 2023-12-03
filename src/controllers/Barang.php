<?php

class Barang extends BaseController
{

    public function index()
    {
        $data = [
            'title' => 'Barang',
        ];
        $this->view('template/header', $data);
        $this->view('template/sidebar', $data);
        $this->view('barang/index', $data);
        $this->view('template/js', $data);
    }

    public function edit($id)
    {
        echo "Edit from barang {$id}";
    }
}
