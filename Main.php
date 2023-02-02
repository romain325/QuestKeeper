<?php

include "./src/Router.php";
include "./src/AuthEndpoint.php";
include "./src/PlayerEndpoint.php";

// route definition:
// key: method . path
// value: function returning string
$routes = [
    ...getAuthRouterConfig()
];

$router = new Router();
$router->setRouter($routes);
$router->setAuthedRouter([
    "GET/" => function() { return "<p>hello</p>"; },
    ...getPlayerEndpointRoutes()
]);

echo $router->render();
die();