<?php
namespace MyApp\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MyApp\Core\BaseController;
use MyApp\Models\AutentikasiModel;

class BarangController extends BaseController
{
  private $barangModel;

  public function __construct()
  {
    // Menginisialisasi model BarangModel
    $this->barangModel = $this->model('MyApp\Models\BarangModel');
  }

  private function getToken()
  {
    // Mendapatkan token dari header
    $headers = getallheaders();
    if (!isset($headers['Authorization']) || $headers['Authorization'] == "") {
      // Menangani kesalahan jika token tidak diberikan
      $data = [
        'status' => '401',
        'error' => '401',
        'message' => 'Access tidak diberikan',
        'data' => null
      ];
      $this->view('template/header');
      header('HTTP/1.0 401 Unauthorized');
      echo json_encode($data);
      exit();
    }

    // Mendekode token menggunakan kunci yang disimpan di ENV
    list(, $token) = explode(' ', $headers['Authorization']);
    try {
      $decodedToken = JWT::decode($token, new Key(getenv('JWT_SECRET_KEY'), 'HS256'));
      $authModel = new AutentikasiModel();
      $dataModel = $authModel->getByEmail($decodedToken->email);
      return $dataModel;
    } catch (\Exception $e) {
      // Menangani kesalahan jika token tidak valid
      $data = [
        'status' => '401',
        'error' => '401',
        'message' => 'Token tidak valid',
        'data' => null
      ];
      $this->view('template/header');
      header('HTTP/1.0 401 Unauthorized');
      echo json_encode($data);
      return null;
    }
  }

  public function index($id = null)
  {
    if ($this->getToken()) {
      if ($id == null) {
        try {
          // Mendapatkan semua data barang
          $data = $this->barangModel->getAll();
        } catch (\Exception $e) {
          // Menangani kesalahan jika terjadi internal server error
          $data = [
            'status' => '500',
            'error' => '500',
            'message' => 'Internal Server Error',
            'data' => null
          ];
          $this->view('template/header');
          header('HTTP/1.0 500 Internal Server Error');
          echo json_encode($data);
          exit();
        }
      } else {
        try {
          // Mendapatkan data barang berdasarkan ID
          $data = $this->barangModel->getById($id);
        } catch (\Exception $e) {
          // Menangani kesalahan jika terjadi internal server error
          $data = [
            'status' => '500',
            'error' => '500',
            'message' => 'Internal Server Error',
            'data' => null
          ];
          $this->view('template/header');
          header('HTTP/1.0 500 Internal Server Error');
          echo json_encode($data);
          exit();
        }
      }

      if ($data) {
        // Menampilkan data jika ditemukan
        $data = [
          'status' => '200',
          'error' => null,
          'message' => 'Data ditemukan',
          'data' => $data
        ];
        $this->view('template/header');
        header('HTTP/1.0 200 OK');
        echo json_encode($data);
      } else {
        // Menangani jika data tidak ditemukan
        $data = [
          'status' => '404',
          'error' => '404',
          'message' => 'Data tidak ditemukan',
          'data' => null
        ];
        $this->view('template/header');
        header('HTTP/1.0 404 Not Found');
        echo json_encode($data);
      }
    }
  }

  // Fungsi untuk menambahkan data barang
  public function insert()
  {
    if ($this->getToken()) {
      // Mendapatkan input data dari body request
      $data = json_decode(file_get_contents('php://input'), true);
      
      // Mendefinisikan aturan validasi untuk input data
      $fields = [
        'nama_barang' => 'string | required',
        'jumlah' => 'int | required',
        'harga_satuan' => 'float | required',
        'kadaluarsa' => 'string'
      ];
      
      // Pesan kesalahan yang akan ditampilkan jika validasi gagal
      $message = [
        'nama_barang' => [
          'required' => 'Nama Barang harus diisi!',
          'alphanumeric' => 'Masukkan huruf dan angka',
          'between' => 'Nama Barang harus di antara 3 dan 25 karakter',
        ],
        'jumlah' => [
          'required' => 'Jumlah harus diisi!',
        ],
        'harga_satuan' => [
          'required' => 'Harga Satuan harus diisi!',
        ]
      ];

      // Melakukan validasi input data
      [$inputs, $errors] = $this->filter($data, $fields, $message);

      // Mengatasi nilai default jika tanggal kadaluarsa tidak diisi
      if ($inputs['kadaluarsa'] == "") {
        $inputs['kadaluarsa'] = "0000-00-00";
      }

      if ($errors) {
        // Menangani kesalahan jika validasi gagal
        $data = [
          'status' => '400',
          'error' => '400',
          'message' => $errors,
          'data' => $inputs
        ];
        $this->view('template/header');
        header('HTTP/1.0 400 Bad Request');
        echo json_encode($data);
        exit();
      } else {
        // Menambahkan data barang
        $proc = $this->barangModel->insert($inputs);
        if ($proc->rowCount() > 0) {
          // Menangani jika data berhasil ditambahkan
          $data = [
            'status' => '201',
            'error' => null,
            'message' => "Data ditambahkan " . $proc->rowCount() . " baris",
            'data' => $inputs
          ];
          $this->view('template/header');
          header('HTTP/1.0 201 OK');
          echo json_encode($data);
        } else {
          // Menangani jika data gagal ditambahkan
          $data = [
            'status' => '400',
            'error' => '400',
            'message' => 'Data gagal ditambahkan',
            'data' => null
          ];
          $this->view('template/header');
          header('HTTP/1.0 400 Bad Request');
          echo json_encode($data);
        }
      }
    }
  }

  // Fungsi untuk mengedit data barang
  public function edit($id = null)
  {
    if ($this->getToken()) {
      // Mendapatkan input data dari body request
      $data = json_decode(file_get_contents('php://input'), true);
      
      // Mendefinisikan aturan validasi untuk input data
      $fields = [
        'nama_barang' => 'string | required',
        'jumlah' => 'int | required',
        'harga_satuan' => 'float | required',
        'kadaluarsa' => 'string'
      ];
      
      // Pesan kesalahan yang akan ditampilkan jika validasi gagal
      $message = [
        'nama_barang' => [
          'required' => 'Nama Barang harus diisi!',
          'alphanumeric' => 'Masukkan huruf dan angka',
          'between' => 'Nama Barang harus di antara 3 dan 25 karakter',
        ],
        'jumlah' => [
          'required' => 'Jumlah harus diisi!',
        ],
        'harga_satuan' => [
          'required' => 'Harga Satuan harus diisi!',
        ]
      ];

      // Melakukan validasi input data
      [$inputs, $errors] = $this->filter($data, $fields, $message);

      // Mengatasi nilai default jika tanggal kadaluarsa tidak diisi
      if ($inputs['kadaluarsa'] == "") {
        $inputs['kadaluarsa'] = "0000-00-00";
      }
      $inputs['id'] = $id;

      if ($errors) {
        // Menangani kesalahan jika validasi gagal
        $data = [
          'status' => '400',
          'error' => '400',
          'message' => $errors,
          'data' => $inputs
        ];
        $this->view('template/header');
        header('HTTP/1.0 400 Bad Request');
        echo json_encode($data);
        exit();
      } else {
        // Mengupdate data barang
        $proc = $this->barangModel->update($inputs);
        if ($proc->rowCount() > 0) {
          // Menangani jika data berhasil diperbarui
          $data = [
            'status' => '201',
            'error' => null,
            'message' => "Data diperbarui " . $proc->rowCount() . " baris",
            'data' => $inputs
          ];
          $this->view('template/header');
          header('HTTP/1.0 201 OK');
          echo json_encode($data);
          exit();
        } else {
          // Menangani jika data gagal diperbarui atau tidak ada perubahan data
          $data = [
            'status' => '400',
            'error' => '400',
            'message' => 'Data gagal diperbarui atau tidak ada perubahan data',
            'data' => null
          ];
          $this->view('template/header');
          header('HTTP/1.0 400 Bad Request');
          echo json_encode($data);
          exit();
        }
      }
    }
  }

  // Fungsi untuk menghapus data barang
  public function delete($id = null)
  {
    if ($this->getToken()) {
      if ($id == null) {
        // Menangani jika ID tidak diberikan
        $data = [
          'status' => '404',
          'error' => '404',
          'message' => 'Data tidak ditemukan',
          'data' => null
        ];
        $this->view('template/header');
        header('HTTP/1.0 404 Not Found');
        echo json_encode($data);
        exit();
      } else {
        // Menghapus data barang berdasarkan ID
        $proc = $this->barangModel->delete($id);
        if ($proc->rowCount() > 0) {
          // Menangani jika data berhasil dihapus
          $data = [
            'status' => '200',
            'error' => null,
            'message' => 'Data berhasil dihapus',
            'data' => [
              'barang_id' => $id
            ]
          ];
          $this->view('template/header');
          header('HTTP/1.0 200 OK');
          echo json_encode($data);
          exit();
        } else {
          // Menangani jika data tidak ditemukan
          $data = [
            'status' => '404',
            'error' => '404',
            'message' => 'Data tidak ditemukan',
            'data' => [
              'barang_id' => $id
            ]
          ];
          $this->view('template/header');
          header('HTTP/1.0 404 Not Found');
          echo json_encode($data);
          exit();
        }
      }
    }
  }
}
