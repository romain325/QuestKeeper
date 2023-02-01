<?php

require_once $_SERVER["DOCUMENT_ROOT"]."src/model/User.php";

function getUserFromName(string $name) : User {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM \"QuestKeeper\".user WHERE name=:name;");
    $stmt->execute(['name' => $name]);
    return new User($stmt->fetch());
}

function getUserFromToken(string $token) : User {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT token FROM \"QuestKeeper\".authedclient WHERE token=?");
    $stmt->execute([$token]);
    return new User($stmt->fetch());
}

function isConnected() : bool {
    $pdo = PDOService::getPDO();
    $token = apache_request_headers()['Authorization'];
    $token = explode(" ", $token);
    if(count($token) < 2) return false;
    $stmt = $pdo->prepare("SELECT count(1) FROM \"QuestKeeper\".authedclient WHERE token=?");
    $stmt->execute([$token[1]]);
    return $stmt->fetch()["count"] >= 1;
}