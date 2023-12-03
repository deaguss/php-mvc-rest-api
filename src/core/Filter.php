<?php

class Filter
{
    public function filter(
        array $data,
        array $fields,
        array $messages = []
    ): array {
        $sanitization = [];
        $validation = [];

        // Memisahkan aturan sanitasi dan validasi untuk setiap field
        foreach ($fields as $field => $rules) {
            if (strpos($rules, '|')) {
                // Jika terdapat '|' dalam aturan, pisahkan aturan sanitasi dan validasi
                [$sanitization[$field], $validation[$field]] = explode('|', $rules, 2);
            } else {
                // Jika tidak terdapat '|', gunakan aturan sanitasi saja
                $sanitization[$field] = $rules;
            }
        }

        // Membuat objek Sanitization untuk melakukan proses sanitasi
        $sanitize = new Sanitization();

        // Menjalankan proses sanitasi pada data input
        $inputs = $sanitize->sanitize($data, $sanitization);

        // Contoh penggunaan validasi (belum diimplementasikan)
        // $validate = new Validation();
        // $errors = $validate->validate($inputs, $validation, $messages);

        // Mengembalikan data yang telah disanitasi
        return [$inputs];
    }
}
