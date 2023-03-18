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

function createItem(string $name, string $desc) {
    $pdo = PDOService::getPDO();
    $id = PDOService::getUUID();
    $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".item(id, name, description) VALUES(?,?,?);");
    $stmt->execute([$id, $name, $desc]);
    return [
        "id" => $id,
        "name" => $name,
        "desc" => $desc
    ];
}