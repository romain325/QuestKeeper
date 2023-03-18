<?php

include "./src/Router.php";
include "./src/AuthEndpoint.php";
include "./src/PlayerEndpoint.php";
include "./src/PartyEndpoint.php";
include "./src/ItemsEndpoint.php";


// route definition:
// key: method . path
// value: function returning string
$router = new Router();

$router->setRouter([
    "GET/phpinfo" => function() { phpinfo(); },
    ...getAuthRouterConfig(),
]);
$router->setAuthedRouter([
    "GET/" => function() { return "<p>hello</p>"; },
    ...getPlayerEndpointRoutes(),
    ...getPartyEndpointRoutes(),
    ...getItemsEndpointRoutes()
]);

echo $router->render();
die();