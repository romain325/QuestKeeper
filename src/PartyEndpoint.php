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

function getPartyPlayersEndpoint() : string {
    $body = getJSONBody();
    return json_encode(getPartyPlayers($body["id"]));
}

function getPartyMasterEndpoint() : string {
    $body = getJSONBody();
    if(!array_key_exists("code", $body) && !array_key_exists("id", $body)) {
        http_response_code(400);
        return json_encode([
            "message" => "missing informations, either code or id"
        ]);
    }

    try {
        return json_encode([
            "master" => getPartyMaster($body["code"], $body["id"])
        ]);
    }catch (InvalidArgumentException | PDOException $e) {
        return json_encode([
            "message" => $e->getMessage()
        ]);
    }
}

function getPartyItemsEndpoint() {
    $body = getJSONBody();
    if(!array_key_exists("id", $body)){
        http_response_code(400);
        return json_encode([ "message" => "missing informations: id" ]);
    }

    return json_encode(getPartyItems($body["id"]));
}

function addPartyItemsEndpoint() {
    $body = getJSONBody();
    if(!array_key_exists("id", $body) || !array_key_exists("items", $body)) {
        http_response_code(400);
        return json_encode([ "message" => "missing informations: id or items" ]);
    }

    addPartyItems($body["id"], $body["items"]);

    return json_encode(getPartyItems($body["id"]));
}

function getPartyEndpointRoutes() : array {
    return [
        "POST/party" => function() { return createPartyEndpoint(); },
        "POST/party/join" => function () { return joinPartyEndpoint(); },
        "POST/party/players" => function() { return getPartyPlayersEndpoint(); },
        "POST/party/master" => function() { return getPartyMasterEndpoint(); },
        "POST/party/items" => function() { return getPartyItemsEndpoint(); },
        "PUT/party/items" => function() { return addPartyItemsEndpoint(); }
    ];
}