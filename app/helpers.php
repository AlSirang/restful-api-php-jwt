<?php

use App\App;
use Database\Connection;


if (!function_exists('connection')) {
  function connection()
  {
    # database connection configuration
    return Connection::make(
      App::get('config')['database']
    );
  }
}
