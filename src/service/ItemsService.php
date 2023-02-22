<?php

function getAllItems() {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->query("SELECT * FROM \"QuestKeeper\".item;");
    $res = [];
    while($item = $stmt->fetch()) {
        $res[] = new Item($item);
    }
    return $res;
}