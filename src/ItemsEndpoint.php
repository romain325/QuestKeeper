<?php
include_once 'service/ItemsService.php';


function getItemsEndpointRoutes() : array {
    return [
        "GET/items" => function() { return json_encode(getAllItems()); },
    ];
}