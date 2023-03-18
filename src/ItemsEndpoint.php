<?php
include_once 'service/ItemsService.php';

function createItemEndpoint() : string {
    $body = getJSONBody();
    try {
        return json_encode(createItem($body["name"], $body["desc"]));
    }catch (Exception $e) {
        http_response_code(400);
        return json_encode(["message" => $e->getMessage()]);
    }
}

function getItemsEndpointRoutes() : array {
    return [
        "GET/items" => function() { return json_encode(getAllItems()); },
        "POST/item" => function() { return createItemEndpoint(); }
    ];
}