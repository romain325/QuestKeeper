<?php

require_once $_SERVER["DOCUMENT_ROOT"]."src/model/Player.php";
include_once "AuthService.php";

function getCurrentPlayer() : Player|null {
    try {
        return getCurrentUser()->getPlayer();
    } catch (HttpHeaderException $e) {
        error_log($e->getMessage());
        return null;
    }
}

function getUserPlayers(string $userId) : array {
    $res = [];
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT player_id FROM \"QuestKeeper\".userplayer WHERE user_id=?");
    $stmt->execute([$userId]);

    while($row = $stmt->fetch()){
        $res[] = getPlayerById($row["player_id"]);
    }
    return $res;
}

function setCurrentPlayer(string $userId, string $id) : bool {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("UPDATE \"QuestKeeper\".user
                                SET current_avatar=:playerId
                                WHERE id=:userId;");
    $stmt->execute([
        "playerId" => $id,
        "userId" => $userId
    ]);
    return $id;
}

function getPlayerById(string $id) : Player {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM \"QuestKeeper\".player WHERE id=?");
    $stmt->execute([$id]);
    $playerData = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT s.* FROM \"QuestKeeper\".player p 
                INNER JOIN \"QuestKeeper\".playerstat ps ON p.id = ps.id_player
                INNER JOIN \"QuestKeeper\".stat s ON ps.id_stat = s.id
                WHERE p.id=?");
    $stmt->execute([$id]);
    $playerStats = $stmt->fetchAll();


    $stmt = $pdo->prepare("SELECT i.* FROM \"QuestKeeper\".player p 
                INNER JOIN \"QuestKeeper\".inventory inv ON p.id = inv.id_player
                INNER JOIN \"QuestKeeper\".item i ON inv.id_item = i.id
                WHERE p.id=?");
    $stmt->execute([$id]);
    $playerInv = $stmt->fetchAll();

    return new Player($playerData, $playerInv, $playerStats);
}