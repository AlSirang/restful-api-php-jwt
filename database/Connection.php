<?php

namespace Database;

use PDO;

class Connection
{

  public static function make($configs)
  {

    try {
      return new PDO(
        "mysql:host=" . $configs['host'] . ";dbname=" . $configs['dbname'],
        $configs['user'],
        $configs['password']
      );
    } catch (\Throwable $th) {
      die($th->getMessage());
    }
  }
}
