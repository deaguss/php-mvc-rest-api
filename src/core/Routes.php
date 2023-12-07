<?php

namespace MyApp\Core;    

class Routes
{

    public function run()
    {
        $router = new App();
        $router->setDefaultController('DefaultApp');
        $router->setDefaultMethod('index');
        
        $router->get('/barang', ['Barang', 'index']);
        $router->get('/barang/(:id)', ['Barang', 'index']);
        $router->patch('/barang/(:id)', ['Barang', 'edit']);
        $router->post('/barang', ['Barang', 'insert']);
        $router->delete('/barang/(:id)', ['Barang','delete']);

        // $router->get('/barang', ['Barang', 'index']);
        // $router->get('/barang/index', ['Barang', 'index']);
        // $router->get('/barang/insert', ['Barang', 'insert']);
        // $router->get('/barang/edit/(:id)', ['Barang', 'edit']);

        // $router->post('/barang/insert_barang', ['Barang', 'insert_barang']);
        // $router->post('/barang/update_barang', ['Barang', 'update_barang']);

        $router->get('/kategori', ['Kategori', 'index']);

        

        $router->run();
    }
}
