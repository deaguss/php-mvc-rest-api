<?php
namespace MyApp\Core;

class Sanitization
{
  // Daftar filter yang dapat digunakan untuk sanitasi data
  const FILTERS = [
    'string' => FILTER_SANITIZE_SPECIAL_CHARS,
    'string[]' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'email' => FILTER_SANITIZE_EMAIL,
    'int' => [
      'filter' => FILTER_SANITIZE_NUMBER_INT,
      'flags' => FILTER_REQUIRE_SCALAR
    ],
    'int[]' => [
      'filter' => FILTER_SANITIZE_NUMBER_INT,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'float' => [
      'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
      'flags' => FILTER_FLAG_ALLOW_FRACTION
    ],
    'float[]' => [
      'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'url' => FILTER_SANITIZE_URL,
  ];

  // Fungsi untuk menghapus whitespace dari elemen-elemen array
  private function array_trim(array $items): array
  {
    return array_map(function ($item) {
      if (is_string($item)) {
        return trim($item);
      } elseif (is_array($item)) {
        return $this->array_trim($item);
      } else {
        return $item;
      }
    }, $items);
  }

  // Fungsi untuk melakukan sanitasi data berdasarkan filter yang ditentukan
  public function sanitize(
    array $inputs,
    array $fields = [],
    int $default_filter = FILTER_SANITIZE_SPECIAL_CHARS,
    array $filters = self::FILTERS,
    bool $trim = true
  ): array {
    // Jika terdapat daftar field yang diinginkan, proses sanitasi berdasarkan filter yang ditentukan
    if ($fields) {
      foreach ($fields as $key => $field) {
        // Jika field adalah string, hilangkan tag HTML
        if ($field == "string" && isset($inputs[$key])) {
          $tempvar = strip_tags($inputs[$key]);
          $inputs[$key] = $tempvar;
        }
      }
      // Konversi nama field menjadi filter yang sesuai, dan lakukan sanitasi
      $options = array_map(fn($field) => $filters[trim($field)], $fields);
      $data = filter_var_array($inputs, $options);
    } else {
      // Jika tidak ada daftar field yang diinginkan, gunakan filter default
      $data = filter_var_array($inputs, $default_filter);
    }

    // Jika trim diaktifkan, hapus whitespace dari data
    return $trim ? $this->array_trim($data) : $data;
  }
}
