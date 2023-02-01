<?php

include "./src/Router.php";
include "./src/AuthEndpoint.php";

// route definition:
// key: method . path
// value: function returning string
$routes = [
    "GET/" => function() { return "<p>hello</p>"; },
    "POST/login" => function() { return login(); },
    "POST/disconnect" => function() {return disconnect(); },
];

$router = new Router();
$router->setRouter($routes);

echo $router->render();
die();