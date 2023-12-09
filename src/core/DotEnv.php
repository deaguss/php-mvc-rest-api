<?php

namespace MyApp\Core;

class DotEnv
{
  // Properti untuk menyimpan path file env
  protected $path;

  // Konstruktor kelas, menerima path file env sebagai argumen
  public function __construct(string $path)
  {
    // Mengecek apakah file env ada
    if (!file_exists($path)) {
      throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
    }
    $this->path = $path;
  }

  // Metode untuk memuat variabel lingkungan dari file env
  public function load(): void
  {
    // Mengecek apakah file env dapat dibaca
    if (!is_readable($this->path)) {
      throw new \InvalidArgumentException(printf('"%s" is not readable', $this->path));
    }

    // Membaca file env baris per baris
    $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      // Melewati komentar yang dimulai dengan tanda pagar (#)
      if (strpos(trim($line), '#') === 0) {
        continue;
      }

      // Membagi baris menjadi nama dan nilai variabel
      list($name, $value) = explode('=', $line, 2);
      $name = trim($name);
      $value = trim($value);

      // Menyimpan variabel lingkungan jika belum ada
      if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
      }
    }
  }
}
