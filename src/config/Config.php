<?php

namespace MyApp\Config;

use MyApp\Core\DotEnv;


class Config
{
  // Metode untuk memuat konfigurasi
  public static function load()
  {
    // Buat objek DotEnv dan muat file .env dari direktori saat ini
    (new DotEnv(__DIR__ . '../../.env'))->load();

    // Tetapkan konstanta BASEURL dengan nilai dari variabel lingkungan BASE_URL
    define('BASEURL', getenv('BASE_URL'));
  }
}
