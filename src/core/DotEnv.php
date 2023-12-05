<?php

namespace MyApp\Core;    


class DotEnv
{
  /**
   * The directory where the .env file can be located.
   *
   * @var string
   */
  protected $path;

  /**
   * Constructor untuk kelas DotEnv.
   *
   * @param string $path - Path dari file .env.
   * @throws \InvalidArgumentException - Jika file .env tidak ditemukan.
   */
  public function __construct(string $path)
  {
    if (!file_exists($path)) {
      throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
    }
    $this->path = $path;
  }

  /**
   * Metode untuk memuat variabel lingkungan dari file .env.
   *
   * @throws \RuntimeException - Jika file .env tidak dapat dibaca.
   */
  public function load(): void
  {
    if (!is_readable($this->path)) {
      throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
    }

    // Membaca setiap baris dari file .env
    $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

      // Mengabaikan komentar yang dimulai dengan #
      if (strpos(trim($line), '#') === 0) {
        continue;
      }

      // Memecah baris menjadi nama dan nilai variabel
      list($name, $value) = explode('=', $line, 2);
      $name = trim($name);
      $value = trim($value);

      // Menetapkan variabel lingkungan jika belum ada
      if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
      }
    }
  }
}
