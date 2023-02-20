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

function joinPartyEndpoint() : string {
    $body = getJSONBody();
    if(!array_key_exists("code", $body)) {
        http_response_code(400);
        return json_encode([
            "message" => "Missing party code"
        ]);
    }

    try {
        return json_encode([
            "result" => joinParty($body["code"])
        ]);
    } catch (HttpHeaderException $e) {
        http_response_code(500);
        return json_encode([
            "message" => "Error while creating party"
        ]);
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        return json_encode([
            "message" => $e->getMessage()
        ]);
    }
}

function getPartyEndpointRoutes() : array {
    return [
        "POST/party" => function() { return createPartyEndpoint(); },
        "POST/party/join" => function () { return joinPartyEndpoint(); }
    ];
}