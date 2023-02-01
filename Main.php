<?php

include "./src/Router.php";
include "./src/AuthEndpoint.php";

// route definition:
// key: method . path
// value: function returning string
$routes = [
    "GET/" => function() { return "<p>hello</p>"; },
    ...getAuthRouterConfig()
];

$router = new Router();
$router->setRouter($routes);

echo $router->render();
die();