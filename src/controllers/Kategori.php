<?php

use MyApp\Core\BaseController;

class Kategori extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Kategori',
        ];
        $this->view('template/header', $data);
        $this->view('template/sidebar', $data);
        $this->view('kategori/index', $data);
        $this->view('template/js', $data);
    }
}
