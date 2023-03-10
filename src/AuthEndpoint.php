<?php

include "service/PDOService.php";
include "service/AuthService.php";
include "utils/HttpUtils.php";

function login() : string {
    $pdo = PDOService::getPDO();
    try {
        $user = getUserFromName($_POST["login"]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        http_response_code(400);
        return "";
    }
    if(sha1($_POST["password"]) == $user->getPassword()) {
        $checkAuth = $pdo->prepare("SELECT token FROM \"QuestKeeper\".authedclient WHERE user_id=?");
        $checkAuth->execute([$user->getId()]);
        if($checkAuth->rowCount() > 0){
            return json_encode([
                "user_id" => $user->getId(),
                "token" => $checkAuth->fetch()["token"]
            ]);
        }
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".authedclient(user_id, token) VALUES(?,?);");
        $stmt->execute([$user->getId(), $token]);
        return json_encode([
            "user_id" => $user->getId(),
            "token" => $token
        ]);
    } else {
        http_response_code(301);
        return "";
    }
}

function disconnect() {
    $pdo = PDOService::getPDO();
    $body = json_decode(file_get_contents("php://input"), true);
    $result = $pdo->prepare("DELETE FROM \"QuestKeeper\".authedclient WHERE token=? AND user_id=?")->execute([$body->token, $body->user_id]);
    if($result) {
        return json_encode([
            "status" => "disconnected",
            "user_id" => $body["user_id"]
        ]);
    } else {
        http_response_code(301);
        return "";
    }
}

function signup() {
    $body = getJSONBody();
    if($body["username"] == null || $body["password"] == null) {
        http_response_code(400);
        return json_encode(["message" => "missing informations"]);
    }

    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(1) FROM \"QuestKeeper\".user WHERE name=?");
    $stmt->execute([$body["username"]]);
    if((int)$stmt->fetch()["count"] > 0) {
        http_response_code(400);
        return json_encode(["message" => "User already exists"]);
    }

    $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".user(name, password_hash) VALUES(?,?)");
    $stmt->execute([ $body["username"], sha1($body["password"]) ]);
    return json_encode([
        "message" => "account succesfully created",
        "username" => $body["username"]
    ]);
}

function getAuthRouterConfig() : array {
    return [
        "POST/login" => function() { return login(); },
        "POST/disconnect" => function() {return disconnect(); },
        "POST/signup" => function() {return signup(); },
    ];
}