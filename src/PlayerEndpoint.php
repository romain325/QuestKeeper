<?php

include_once 'service/PlayerService.php';
include_once 'service/AuthService.php';

function getCurrentUserPlayer() : string {
    return json_encode(getCurrentPlayer(), JSON_UNESCAPED_UNICODE);
}

function getCurrentUserPlayers() : string {
    try {
        return json_encode(getUserPlayers(getCurrentUser()->getId()), JSON_UNESCAPED_UNICODE);
    } catch (HttpHeaderException $e) {
        return json_encode(array());
    }
}

function setCurrentPlayerEndpoint() : bool {
    $id = getJSONBody()["id"];
    if(!$id) {
        http_response_code(400);
        return json_encode([
            "message" => "missing informations"
        ]);
    }
    try {
        return json_encode([
            "id" => setCurrentPlayer(getCurrentUser()->getId(), $id)
        ]);
    } catch (HttpHeaderException $e) {
        http_response_code(500);
        return json_encode([
            "message" => $e->getMessage()
        ]);
    }
}

function createPlayerEndpoint() : string {
    $body = getJSONBody();
    try {
        $dbPlayer = createPlayer(new Player($body, $body["playerItems"], $body["playerStats"]), getCurrentUser()->getId());
        if($dbPlayer){
            return json_encode($dbPlayer);
        } else {
            http_response_code(400);
            return json_encode([
                "message" => "error while creating player"
            ]);
        }
    } catch (HttpHeaderException $e) {
        http_response_code(304);
        return json_encode([
            "message" => "error retrieving user"
        ]);
    }

}

function deletePlayerEndpoint() {
    $body = getJSONBody();
    deletePlayer($body["id"]);
    return "";
}

function getPlayerEndpointRoutes() : array {
    return [
        "GET/player" => function() { return getCurrentUserPlayer(); },
        "GET/players" => function() { return getCurrentUserPlayers(); },
        "PUT/player" => function() { return setCurrentPlayerEndpoint(); },
        "POST/player" => function() { return createPlayerEndpoint(); },
        "DELETE/player" => function() { return deletePlayerEndpoint(); }
    ];
}