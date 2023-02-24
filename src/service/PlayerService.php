<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/src/model/Player.php";
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

function createPlayer(Player $player, string $userId) : Player{
    $pdo = PDOService::getPDO();
    $stats = [];
    $playerId = PDOService::getUUID();

    foreach ($player->playerStats as $stat) {
        $id = PDOService::getUUID();
        $stats[] = $id;
        $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".stat(id, name, value) VALUES(?, ?, ?);");
        $stmt->execute([$id, $stat->name, $stat->value]);
    }

    $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".player(id, \"name\", \"desc\") VALUES(?, ?, ?);");
    $stmt->execute([$playerId, $player->name, $player->desc]);

    foreach($stats as $statId) {
        $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".playerstat(id_player, id_stat)VALUES(?, ?);");
        $stmt->execute([$playerId, $statId]);
    }

    $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".userplayer(user_id, player_id) VALUES(?,?);");
    $stmt->execute([$userId, $playerId]);

    return getPlayerById($playerId);
}

function deletePlayer(string $id) {
    $pdo = PDOService::getPDO();
    $stats = [];

    $stmt = $pdo->prepare("SELECT * FROM \"QuestKeeper\".user WHERE current_avatar = ?;");
    $stmt->execute([$id]);
    if($stmt->rowCount() > 0) {
        while($row = $stmt->fetch()) {
            setCurrentPlayer($row["id"], null);
        }
    }


    $stmt = $pdo->prepare("SELECT id_stat FROM \"QuestKeeper\".playerstat WHERE id_player=?");
    $stmt->execute([$id]);
    $stats = [];
    while($row = $stmt->fetch()) {
        $stats[] = $row["id_stat"];
    }

    if(count($stats) > 0) {
        $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".playerstat WHERE id_player=?");
        $stmt->execute([$id]);

        $placeholders = str_repeat ('?, ',  count ($stats) - 1) . '?';
        $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".stat WHERE id in ($placeholders)");
        $stmt->execute($stats);
    }

    $stmt = $pdo->prepare("SELECT id_item FROM \"QuestKeeper\".inventory WHERE id_player=?");
    $stmt->execute([$id]);
    $items = [];
    while($row = $stmt->fetch()) {
        $items[] = $row["id_item"];
    }

    if(count($items) > 0) {
        $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".inventory WHERE id_player=?");
        $stmt->execute([$id]);

        $placeholders = str_repeat ('?, ',  count ($items) - 1) . '?';
        $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".item WHERE id in($placeholders)");
        $stmt->execute($items);
    }

    $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".userplayer where player_id=?");
    $stmt->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".player WHERE id=?");
    $stmt->execute([$id]);
}

function addItemToPlayer(string $playerId, string $itemId) : bool {
    $pdo = PDOService::getPDO();
    try {
        $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".inventory(id_player, id_item) VALUES(?,?);");
        $stmt->execute([$playerId, $itemId]);
    } catch (PDOException $e) {
        return false;
    }
    return true;
}