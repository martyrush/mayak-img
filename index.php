<?php
    require_once('config/config.php');
    require_once('config/routes.php');
    $uri =$_SERVER['REQUEST_URI'];
  

    $routes=new Router();

    $routes->addRoute('/travel/', 'Search');
    $routes->addRoute('/travel/parse', 'Parser');
    $routes->addRoute('/travel/api/getImages', 'Api');
    $routes->addRoute('/travel/api/getImageSize','Api@getSizeImages');
  
    $routes->dispatch($uri);

?>