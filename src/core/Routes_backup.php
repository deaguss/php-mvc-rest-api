<?php

// Ini adalah class Routes yang bertanggung jawab untuk mengatur routing dalam aplikasi web.

class Routes
{
    // Inisialisasi nilai default untuk file controller dan method controller.
    private $controllerFile = 'DefaultApp';
    private $controllerMethod = 'index';

    // Array untuk menyimpan parameter URL.
    private $parameter = [];

    // Fungsi run() akan dijalankan untuk menangani routing.
    public function run()
    {
        // Dapatkan URL dari fungsi getUrl().
        $url = $this->getUrl();

        // Cek apakah file controller yang diminta ada.
        if ($url && file_exists(__DIR__ . '/../controllers/' . $url[0] . '.php')) {
            // Jika ada, atur file controller yang sesuai.
            $this->controllerFile = $url[0];
            unset($url[0]);
        }

        // Include file controller yang telah ditentukan.
        require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';

        // Inisialisasi objek dari file controller.
        $this->controllerFile = new $this->controllerFile;

        // Cek apakah method yang diminta ada dalam file controller.
        if (isset($url[1])) {
            if (method_exists($this->controllerFile, $url[1])) {
                // Jika ada, atur method controller yang sesuai.
                $this->controllerMethod = $url[1];
                unset($url[1]);
            }
        }

        // Jika masih ada parameter URL, simpan ke dalam array $parameter.
        if (!empty($url)) {
            $this->parameter = array_values($url);
        }

        // Panggil method controller dengan parameter yang sesuai.
        call_user_func_array([$this->controllerFile, $this->controllerMethod], $this->parameter);
    }

    // Fungsi untuk mendapatkan URL dari server dan membersihkannya.
    private function getUrl()
    {
        // Dapatkan URL dari QUERY_STRING dan hilangkan karakter '/' di akhir.
        $url = rtrim($_SERVER['QUERY_STRING'], '/');

        // Bersihkan URL dari karakter-karakter yang tidak diinginkan.
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // Pisahkan URL menjadi array berdasarkan karakter '/'.
        $url = explode('/', $url);

        // Kembalikan array URL.
        return $url;
    }
}
