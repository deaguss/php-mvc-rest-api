<?php

namespace MyApp\Controllers;

use MyApp\Core\BaseController;
use MyApp\Models\AuthModel;

class Authentication extends BaseController {
    private $authModel;
    private const MESSAGE = [
        'email' => [
            'required' => 'Email harus diisi',
            'email' => 'Email tidak valid'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'secure' => 'Password minimal 8 karakter, kombinasi huruf besar, huruf kecil dan karakter khusus!'
        ]
    ];

    public function __construct()
    {
        $this->authModel = $this->model('MyApp\Models\AuthModel');
    }

    public function register()
  {
    $dataUser = json_decode(file_get_contents("php://input"), true);
    if (!$dataUser) {
      $data = [
        'status' => '400',
        'error' => '400',
        'message' => 'Bad Request',
        'data' => null
      ];
      $this->view('template/header');
      header('HTTP/1.1 400 Bad Request');
      echo json_encode($data);
      exit();
    }
    $field = [
      'email' => 'string | required | email | unique: auth, email',
      'password' => 'string | required | secure',
    ];
    [$inputs, $errors] = $this->filter($dataUser, $field, self::MESSAGE);
    if ($errors) {
      $data = [
        'status' => '400',
        'error' => '400',
        'message' => $errors,
        'data' => $inputs
      ];
      $this->view('template/header');
      header('HTTP/1.1 400 Bad Request');
      echo json_encode($data);
      exit();
    }
    else {
      $proc = $this->authModel->insert($inputs);
      if ($proc->rowCount() > 0) {
        $data = [
          'status' => '201',
          'error' => '201',
          'message' => "Data ditambahkan " . $proc->rowCount() . " baris",
          'data' => $inputs
        ];
        $this->view('template/header');
        header('HTTP/1.1 200 OK');
        echo json_encode($data);
        exit();
      } else {
        $data = [
          'status' => '400',
          'error' => '400',
          'message' => "Data gagal ditambahkan",
          'data' => null
        ];
        $this->view('template/header');
        header('HTTP/1.1 400 Bad Request');
        echo json_encode($data);
        exit();
      }
    }
  }


}