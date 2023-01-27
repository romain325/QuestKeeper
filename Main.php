<?php

include "./src/Router.php";

// route definition:
// key: method . path
// value: function returning string
$routes = [
  "GET/" => function() { return "<p>hello</p>"; }
];

$router = new Router();
$router->setRouter($routes);

echo $router->render();
die();