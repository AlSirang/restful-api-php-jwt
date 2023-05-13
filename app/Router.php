<?php

namespace App;

class Router
{

  protected $routes = [
    'GET' => [],
    'POST' => [],
  ];

  public static function requireFile($file)
  {
    $router = new static;
    require $file;
    return $router;
  }


  public function get($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
  }


  public function post($uri, $controller)
  {
    $this->routes['POST'][$uri] = $controller;
  }

  public function show($uri, $method)
  {
    if (array_key_exists($uri, $this->routes[$method]))
      $this->callAction(...explode("@", $this->routes[$method][$uri]));
    else
      http_response_code(404);
  }

  public function callAction($controller, $action)
  {

    $link = "App\\Controllers\\{$controller}";
    $instance = new $link;
    return $instance->$action();
  }
}
