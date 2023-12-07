<?php

namespace MyApp\Core; 

class App
{
    // Properti untuk menyimpan default controller dan method
    private $controllerFile = 'DefaultApp';
    private $controllerMethod = 'index';

    // Properti untuk menyimpan parameter dari URL
    private $parameter = [];

    // Konstanta untuk metode HTTP default (GET, POST, PUT, PATCH, AND DELETE)
    private const DEFAULT_GET = 'GET';
    private const DEFAULT_POST = 'POST';
    private const DEFAULT_PUT = 'PUT';
    private const DEFAULT_PATCH = 'PATCH';
    private const DEFAULT_DELETE = 'DELETE';
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
    public function get($uri, $callback)
    {
        $this->setHandler(self::DEFAULT_GET, $uri, $callback);
    }

    // Metode untuk menambahkan handler untuk metode HTTP POST
    public function post($uri, $callback)
    {
        $this->setHandler(self::DEFAULT_POST, $uri, $callback);
    }

    public function put($uri, $callback)
    {
        $this->setHandler(self::DEFAULT_PUT, $uri, $callback);
    }

    public function delete($uri, $callback)
    {
        $this->setHandler(self::DEFAULT_DELETE, $uri, $callback);
    }
    public function patch($uri, $callback)
    {
        $this->setHandler(self::DEFAULT_PATCH, $uri, $callback);
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
            $path = explode('/', rtrim(ltrim($handler['path'], '/'), '/'));
            $new_path = [];
            $new_url = [];
            $param = [];
            $paramURL = [];
            if (count($path) == count($url)) {
              foreach ($path as $value) {
                if (!str_contains($value, ':')) {
                  array_push($new_path, $value);
                } else {
                  array_push($param, $value);
                }
              }
      
              if (str_contains(implode("/", $url), implode("/", $new_path))) {
      
                for ($i = 0; $i < count($url); $i++) {
                  if ($i < count($new_path)) {
                    array_push($new_url, $url[$i]);
                  } else {
                    array_push($paramURL, $url[$i]);
                  }
                }
      
                if (
                  implode('/', $new_path) == implode('/', $new_url) &&
                  count($param) == count($paramURL) &&
                  $requestMethod == $handler['method']
                ) {
                    if (isset($handler['handler'][0]) && file_exists(__DIR__ . '/../controllers/' . $handler['handler'][0] . '.php')) {
                        $this->controllerFile = $handler['handler'][0];
                    }
    
                    // Memuat file controller dan membuat objek controller
                    require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
                    $this->controllerFile = new $this->controllerFile;
                    $execute = 1;
    
                    // Memeriksa eksistensi method pada controller
                    if (isset($handler['handler'][1]) && method_exists($this->controllerFile, $handler['handler'][1])) {
                        $this->controllerMethod = $handler['handler'][1];
                    }

                    $url = $paramURL;
                }
              }
            }
      
        // Menjalankan default controller jika tidak ada handler yang cocok
        if ($execute == 0) {
            $defaultAppController = new \MyApp\Controllers\DefaultApp;
            $this->controllerFile = $defaultAppController;
        }

        // Menyimpan parameter sisa URL
        if (!empty($url)) {
            $this->parameter = array_values($url);
        }

        // Memanggil method pada controller dengan parameter
        call_user_func_array([$this->controllerFile, $this->controllerMethod], $this->parameter);
        }
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
