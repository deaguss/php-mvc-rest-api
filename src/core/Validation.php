<?php

namespace MyApp\Core;    


class Validation
{
    // Pesan kesalahan validasi default
    const DEFAULT_VALIDATION_ERRORS = [
        'required' => 'Data %s harus diisi',
        'email' => ' %s email tidak valid',
        'min' => '%s harus lebih dari %d karakter',
        'max' => '%s harus kurang dari %d karakter',
        'between' => '%s harus diantara %d and %d karakter',
        'same' => '%s and %s tidak sama',
        'alphanumeric' => '%s harus diisi huruf dan angka',
        'secure' => '%s jumlah diantara 8 and 64 characters and ada angka, huruf besar, huruf kecil and dan karakter spesial',
        'unique' => '%s sudah ada',
    ];

    // Metode utama untuk melakukan validasi
    public function validate(
        array $data,
        array $fields,
        array $messages = []
    ): array {
        $split = fn ($str, $separator) => array_map('trim', explode($separator, $str));

        // Mendapatkan pesan aturan validasi
        $rule_messages = array_filter($messages, fn ($message) => is_string($message));
        // Menimpa pesan kesalahan validasi default dengan pesan khusus yang diberikan
        $validation_errors = array_merge(self::DEFAULT_VALIDATION_ERRORS, $rule_messages);
        $errors = [];

        // Iterasi melalui setiap aturan validasi untuk setiap field
        foreach ($fields as $field => $option) {
            $rules = $split($option, '|');
            foreach ($rules as $rule) {
                $params = [];

                // Memeriksa apakah aturan validasi memiliki parameter
                if (strpos($rule, ':')) {
                    [$rule_name, $param_str] = $split($rule, ':');
                    $params = $split($param_str, ',');
                } else {
                    $rule_name = trim($rule);
                }

                // Membuat nama fungsi validasi
                $fn = 'is_' . $rule_name;

                // Memeriksa apakah fungsi validasi ada
                if (method_exists(new Validation(), $fn)) {
                    // Memanggil fungsi validasi dan mengecek kegagalan
                    $pass = $this->$fn($data, $field, ...$params);

                    // Jika validasi gagal, menambahkan pesan kesalahan ke dalam array
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

        return $errors;
    }

    // Fungsi validasi: Memeriksa apakah suatu field wajib diisi
    public function is_required(
        array $data,
        string $field
    ): bool {
        return isset($data[$field]) && $data[$field] !== '';
    }

    // Fungsi validasi: Memeriksa apakah suatu field berisi alamat email yang valid
    public function is_email(
        array $data,
        string $field
    ): bool {
        if (empty($data[$field])) {
            return true;
        }

        return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
    }

    // Fungsi validasi: Memeriksa apakah panjang suatu field lebih dari atau sama dengan nilai minimum
    public function is_min(
        array $data,
        string $field,
        int $min
    ): bool {
        if (!isset($data[$field])) {
            return true;
        }

        return mb_strlen($data[$field]) >= $min;
    }

    // Fungsi validasi: Memeriksa apakah panjang suatu field kurang dari atau sama dengan nilai maksimum
    public function is_max(
        array $data,
        string $field,
        int $max
    ): bool {
        if (!isset($data[$field])) {
            return true;
        }
        return mb_strlen($data[$field]) <= $max;
    }

    // Fungsi validasi: Memeriksa apakah panjang suatu field berada dalam rentang nilai minimum dan maksimum
    public function is_between(
        array $data,
        string $field,
        int $min,
        int $max
    ): bool {
        if (!isset($data[$field])) {
            return true;
        }
        $len = mb_strlen($data[$field]);
        return $len >= $min && $len <= $max;
    }

    // Fungsi validasi: Memeriksa apakah dua field memiliki nilai yang sama
    public function is_same(
        array $data,
        string $field,
        string $other
    ): bool {
        if (isset($data[$field], $data[$other])) {
            return $data[$field] === $data[$other];
        }

        if (!isset($data[$field]) && !isset($data[$other])) {
            return true;
        }

        return false;
    }

    // Fungsi validasi: Memeriksa apakah suatu field hanya berisi huruf dan angka
    public function is_alphanumeric(
        array $data,
        string $field
    ): bool {
        if (!isset($data[$field])) {
            return true;
        }

        return ctype_alnum($data[$field]);
    }

    // Fungsi validasi: Memeriksa apakah suatu field memenuhi kriteria keamanan tertentu
    public function is_secure(
        array $data,
        string $field
    ): bool {
        if (!isset($data[$field])) {
            return true;
        }
        $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
        return preg_match($pattern, $data[$field]);
    }

    // Fungsi validasi: Memeriksa apakah nilai suatu field unik dalam tabel database
    public function is_unique(array $data, string $field, string $table, string $column): bool
    {
        return false; // belum diimplementasikan
    }

    
}
