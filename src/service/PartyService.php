<?php

function generatePartyCode() : string {
    $alphabets="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $code = "";
    for($i = 0; $i < 6; $i++) {
        $code .= $alphabets[random_int(0, strlen($alphabets)-1)];
    }
    return $code;
}

function joinParty(string $userId, string $partyCode) {
    // if user is mj
    // if user is player
}

/**
 * @return string party code
 * @throws HttpHeaderException
 */
function createParty(string $name) : string {
    $pdo = PDOService::getPDO();
    $code = generatePartyCode();
    $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".party(join_code, name, master) VALUES(?,?,?);");
    $stmt->execute([$code, $name, getCurrentUser()->getId()]);
    return $code;
}