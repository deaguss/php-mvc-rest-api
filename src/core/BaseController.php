<?php

class BaseController extends Filter
{

    public function view($view, $data = [])
    {
        if (count($data)) {
            extract($data);
        }
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    public function redirect($url)
    {
        header('Location: ' . BASE_URL. '/' . $url);
        exit;
    }

    public function model($model)
    {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }
}
