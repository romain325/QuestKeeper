<?php

include "./src/Router.php";
include "./src/AuthEndpoint.php";
include "./src/PlayerEndpoint.php";
include "./src/PartyEndpoint.php";

// route definition:
// key: method . path
// value: function returning string
$router = new Router();
$router->setRouter([
    ...getAuthRouterConfig()
]);
$router->setAuthedRouter([
    "GET/" => function() { return "<p>hello</p>"; },
    ...getPlayerEndpointRoutes(),
    ...getPartyEndpointRoutes()
]);

echo $router->render();
die();