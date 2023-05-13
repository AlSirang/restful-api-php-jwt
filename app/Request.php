<?php

namespace App;

class Request
{
  # uri 
  public static function uri()
  {

    return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  }

  # method
  public static function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  # values
  public static function values()
  {
    return $_REQUEST;
  }

  # files
  public static function files()
  {
    return $_FILES;
  }
}
