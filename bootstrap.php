<?php

use App\{App, JwtAuth, Request, Router};

App::bind('config', require "config.php");

JwtAuth::setSecretKey(App::get('config')['jwt_secret']);

# routes configuration
Router::requireFile('routes.php')->show(
  Request::uri(),
  Request::method()
);
