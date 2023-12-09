<?php
namespace MyApp\Core;

use MyApp\Models\UniqueModel;

class Validation
{
  // Pesan validasi default
  const DEFAULT_VALIDATION_ERRORS = [
    'required' => 'Data %s harus diisi',
    'email' => ' %s email tidak valid',
    'min' => '%s harus lebih dari %d karakter',
    'max' => '%s harus kurang dari %d karakter',
    'between' => '%s harus diantara %d dan %d karakter',
    'same' => '%s dan %s tidak sama',
    'alphanumeric' => '%s harus diisi huruf dan angka',
    'secure' => '%s jumalah diantara 8 dan 64 karakter dan ada angka, huruf besar, huruf kecil dan karakter spesial',
    'unique' => '%s sudah ada',
  ];

  // Fungsi utama untuk validasi data
  public function validate(array $data, array $fields, array $messages = []): array
  {
    // Fungsi untuk memisahkan string berdasarkan separator dan menghapus spasi di sekitarnya
    $split = fn($str, $separator) => array_map('trim', explode($separator, $str));

    // Filter pesan validasi kustom yang valid (hanya string)
    $rule_messages = array_filter($messages, fn($message) => is_string($message));

    // Gabungkan pesan validasi default dengan pesan validasi kustom
    $validation_errors = array_merge(self::DEFAULT_VALIDATION_ERRORS, $rule_messages);

    // Array untuk menyimpan kesalahan validasi
    $errors = [];

    foreach ($fields as $field => $option) {
      // Pisahkan aturan validasi yang diterapkan pada bidang tertentu
      $rules = $split($option, '|');

      foreach ($rules as $rule) {
        $params = [];

        // Jika aturan memiliki parameter (contoh: min:3), pisahkan nama aturan dan parameter
        if (strpos($rule, ':')) {
          [$rule_name, $param_str] = $split($rule, ':');
          $params = $split($param_str, ',');
        } else {
          $rule_name = trim($rule);
        }

        // Bentuk nama fungsi validasi
        $fn = 'is_' . $rule_name;

        // Jika fungsi validasi ada, panggil fungsi tersebut
        if (method_exists(new Validation(), $fn)) {
          $pass = $this->$fn($data, $field, ...$params);

          // Jika validasi gagal, tambahkan pesan kesalahan ke array kesalahan
          if (!$pass) {
            array_push(
              $errors,
              sprintf(
                $messages[$field][$rule_name] ?? $validation_errors[$rule_name],
                str_replace("_", " ", $field),
                ...$params
              )
            );
          }
        }
      }
    }

    // Kembalikan array kesalahan validasi
    return $errors;
  }

  // Fungsi validasi: Bidang wajib diisi
  public function is_required(array $data, string $field): bool
  {
    return isset($data[$field]) && $data[$field] !== '';
  }

  // Fungsi validasi: Format email
  public function is_email(array $data, string $field): bool
  {
    if (empty($data[$field])) {
      return true;
    }

    return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
  }

  // Fungsi validasi: Panjang minimal
  public function is_min(array $data, string $field, int $min): bool
  {
    if (!isset($data[$field])) {
      return true;
    }

    return mb_strlen($data[$field]) >= $min;
  }

  // Fungsi validasi: Panjang maksimal
  public function is_max(array $data, string $field, int $max): bool
  {
    if (!isset($data[$field])) {
      return true;
    }

    return mb_strlen($data[$field]) <= $max;
  }

  // Fungsi validasi: Panjang di antara
  public function is_between(array $data, string $field, int $min, int $max): bool
  {
    if (!isset($data[$field])) {
      return true;
    }

    $len = mb_strlen($data[$field]);
    return $len >= $min && $len <= $max;
  }

  // Fungsi validasi: Sama dengan bidang lain
  public function is_same(array $data, string $field, string $other): bool
  {
    if (isset($data[$field], $data[$other])) {
      return $data[$field] === $data[$other];
    }

    if (!isset($data[$field]) && !isset($data[$other])) {
      return true;
    }

    return false;
  }

  // Fungsi validasi: Alfanumerik (huruf dan angka)
  public function is_alphanumeric(array $data, string $field): bool
  {
    if (!isset($data[$field])) {
      return true;
    }

    return ctype_alnum($data[$field]);
  }

  // Fungsi validasi: Keamanan (karakter tertentu)
  public function is_secure(array $data, string $field): bool
  {
    if (!isset($data[$field])) {
      return true;
    }

    // Pola keamanan untuk memeriksa kombinasi karakter tertentu
    $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";

    return preg_match($pattern, $data[$field]);
  }

  // Fungsi validasi: Unik dalam database
  public function is_unique(array $data, string $field, string $table, string $column): bool
  {
    if (!isset($data[$field])) {
      return true;
    }

    // Disini cek ke database menggunakan model UniqueModel
    $uniqueModel = new UniqueModel();
    $stmt = $uniqueModel->check($table, $column, $data[$field]);

    // Kembalikan true jika tidak ada hasil yang cocok di database
    return $stmt->fetchColumn() === false;
  }

}
