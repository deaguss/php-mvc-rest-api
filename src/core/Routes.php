<?php

class Routes
{

    public function run()
    {
        $router = new App();
        $router->setDefaultController('DefaultApp');
        $router->setDefaultMethod('index');

        $router->get('/barang', ['Barang', 'index']);
        $router->get('/barang/index', ['Barang', 'index']);
        $router->get('/barang/insert', ['Barang', 'insert']);
        $router->get('/barang/edit', ['Barang', 'edit']);

        $router->post('/barang/insert_barang', ['Barang', 'insert_barang']);
        $router->post('/barang/update_barang', ['Barang', 'update_barang']);

        $router->run();
    }
}
