<?php

namespace MyApp\Models;

use MyApp\Core\Database;
use PDO;
class AuthModel extends Database {
    public function __construct()
  {
    parent::__construct();
    $this->setTableName('auth');
    $this->setColumn([
      'email',
      'password'
    ]);
  }

  public function register($data)
  {
    $table = [
      'email' => $data['email'],
      'password' => password_hash($data['password'], PASSWORD_BCRYPT)
    ];
    return $this->insertData($table);
  }

//   public function getByEmail($email)
//   {
//     return $this->get(['email' => $email])->fetch(PDO::FETCH_ASSOC);
//   }
}