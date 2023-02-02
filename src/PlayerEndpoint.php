<?php

include_once 'service/PlayerService.php';

function getCurrentUserPlayer() : string {
    return json_encode(getCurrentPlayer(), JSON_UNESCAPED_UNICODE);
}

function getPlayerEndpointRoutes() : array {
    return [
        "GET/player" => function() { return getCurrentUserPlayer(); }
    ];
}