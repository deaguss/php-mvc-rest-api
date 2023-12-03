<?php
class Sanitization
{
    // Konstanta yang menyimpan jenis-jenis filter untuk sanitasi data
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

    // Metode untuk memangkas spasi putih dari semua elemen dalam array
    private function array_trim(array $items): array
    {
        return array_map(function ($item) {
            if (is_string($item)) {
                // Jika elemen adalah string, hapus spasi putih di awal dan akhir
                return trim($item);
            } elseif (is_array($item)) {
                // Jika elemen adalah array, rekursif panggil fungsi ini untuk memangkas spasi putih dalam array tersebut
                return $this->array_trim($item);
            } else {
                // Jika elemen bukan string atau array, kembalikan elemen tanpa perubahan
                return $item;
            }
        }, $items);
    }

    // Metode untuk menyaring dan membersihkan data input
    public function sanitize(
        array $inputs,            // Data input yang akan disanitasi
        array $fields = [],       // Daftar filter khusus untuk setiap kunci dalam data input
        int $default_filter = FILTER_SANITIZE_SPECIAL_CHARS,  // Filter default jika tidak ada filter yang ditentukan
        array $filters = self::FILTERS,  // Jenis filter yang dapat digunakan untuk sanitasi
        bool $trim = true          // Jika true, maka spasi putih akan dipangkas dari data yang disanitasi
    ): array {
        if ($fields) {
            // Jika terdapat filter khusus, iterasi setiap kunci dan lakukan sanitasi sesuai filter
            foreach ($fields as $key => $field) {
                if ($field == "string" && isset($inputs[$key])) {
                    // Jika filter adalah 'string', hapus tag HTML menggunakan strip_tags
                    $tempvar = strip_tags($inputs[$key]);
                    //$tempvar = trim(preg_replace('/[^A-Za-z0-9 ]/', '', $tempvar));
                    $inputs[$key] = $tempvar;
                }
            }
            // Buat array dari jenis filter untuk setiap kunci
            $options = array_map(fn ($field) => $filters[trim($field)], $fields);
            // Gunakan filter_var_array untuk menyaring data input
            $data = filter_var_array($inputs, $options);
        } else {
            // Jika tidak ada filter khusus, gunakan filter default untuk menyaring data input
            $data = filter_var_array($inputs, $default_filter);
        }

        // Jika trim true, pangkas spasi putih dari semua elemen dalam array
        return $trim ? $this->array_trim($data) : $data;
    }
}
