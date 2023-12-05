<?php

namespace MyApp\Core; 

class App
{
    // Properti untuk menyimpan default controller dan method
    private $controllerFile = 'DefaultApp';
    private $controllerMethod = 'index';

    // Properti untuk menyimpan parameter dari URL
    private $parameter = [];

    // Konstanta untuk metode HTTP default (GET dan POST)
    private const DEFAULT_GET = 'GET';
    private const DEFAULT_POST = 'POST';

    // Array untuk menyimpan handler (URL dan callback) berdasarkan metode HTTP
    private $handlers = [];

    // Metode untuk mengatur default controller
    public function setDefaultController($controller)
    {
        $this->controllerFile = $controller;
    }

    // Metode untuk mengatur default method
    public function setDefaultMethod($method)
    {
        $this->controllerMethod = $method;
    }

    // Metode untuk menambahkan handler untuk metode HTTP GET
    public function get($url, $callback)
    {
        $this->setHandler(self::DEFAULT_GET, $url, $callback);
    }

    // Metode untuk menambahkan handler untuk metode HTTP POST
    public function post($url, $callback)
    {
        $this->setHandler(self::DEFAULT_POST, $url, $callback);
    }

    // Metode untuk menetapkan handler
    private function setHandler(string $method, string $path, $handler)
    {
        $this->handlers[$method . $path] = [
            'handler' => $handler,
            'method' => $method,
            'path' => $path,
        ];
    }

    // Metode untuk menjalankan aplikasi
    public function run()
    {
        // Inisialisasi variabel
        $execute = 0;
        $url = $this->getUrl();
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Iterasi melalui handler untuk mencocokkan URL dan metode HTTP
        foreach ($this->handlers as $handler) {
            // Memproses path dan URL
            $path = explode('/', ltrim(rtrim($handler['path'], '/'), '/'));
            $keyPath = (isset($path[0]) ? $path[0] : '') . (isset($path[1]) ? $path[1] : '');
            $keyUrl = (isset($url[0]) ? $url[0] : '') . (isset($url[1]) ? $url[1] : '');

            // Memeriksa kesamaan URL, path, dan metode HTTP
            if ($url != "" && $keyUrl == $keyPath && $requestMethod == $handler['method']) {
                // Memeriksa eksistensi file controller
                if (isset($handler['handler'][0]) && file_exists(__DIR__ . '/../controllers/' . $handler['handler'][0] . '.php')) {
                    $this->controllerFile = $handler['handler'][0];
                    unset($url[0]);
                }

                // Memuat file controller dan membuat objek controller
                require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
                $this->controllerFile = new $this->controllerFile;
                $execute = 1;

                // Memeriksa eksistensi method pada controller
                if (isset($handler['handler'][1]) && method_exists($this->controllerFile, $handler['handler'][1])) {
                    $this->controllerMethod = $handler['handler'][1];
                    unset($url[1]);
                }
            }
        }

        // Menjalankan default controller jika tidak ada handler yang cocok
        if ($execute == 0) {
            require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
            $this->controllerFile = new $this->controllerFile;
        }

        // Menyimpan parameter sisa URL
        if (!empty($url)) {
            $this->parameter = array_values($url);
        }

        // Memanggil method pada controller dengan parameter
        call_user_func_array([$this->controllerFile, $this->controllerMethod], $this->parameter);
    }

    // Metode untuk mendapatkan URL
    private function getUrl()
    {
        // Mendapatkan URL dari query string
        $url = rtrim($_SERVER['QUERY_STRING'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        return $url;
    }
}
