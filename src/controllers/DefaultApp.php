<?php

namespace MyApp\Controllers;

use MyApp\Core\BaseController;

class DefaultApp extends BaseController
{
    public function index()
    {
        $data = [
            'status' => '404',
            'error' => '404',
            'message' => 'Page Not Found',
            'data' => null
        ];
        $this->view('template/header');
        header('HTTP/1.0 404 Not Found');
        echo json_encode($data);
    }
}
