<?php

include_once 'service/PartyService.php';

function createPartyEndpoint() :string {
    $body = getJSONBody();
    if(!array_key_exists("name", $body)) {
        http_response_code(400);
        return json_encode([
            "message" => "Missing informations in request"
        ]);
    }

    try {
        return json_encode([
            "code" => createParty($body["name"])
        ]);
    } catch (HttpHeaderException $e) {
        http_response_code(500);
        return json_encode([
            "message" => "Error while creating party"
        ]);
    }
}

function getPartyEndpointRoutes() : array {
    return [
        "POST/party" => function() { return createPartyEndpoint(); },
    ];
}