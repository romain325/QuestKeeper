<?php

include "service/PDOService.php";

function login() : string {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM \"QuestKeeper\".user WHERE name=:name;");
    $stmt->execute(['name' => $_POST["login"]]);
    $result = $stmt->fetch();
    if(sha1($_POST["password"]) == $result["password_hash"]) {
        $stmt = $pdo->prepare("SELECT token FROM \"QuestKeeper\".authedclient WHERE user_id=?");
        $stmt->execute([$result["id"]]);
        if($stmt->columnCount() >= 1){
            return json_encode([
                "user_id" => $result["id"],
                "token" => $stmt->fetch()["token"]
            ]);
        }
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".authedclient(user_id, token) VALUES(?,?);");
        $result = $stmt->execute([$result["id"], $token]);
        return json_encode([
            "user_id" => $result["id"],
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
    }

}