<?php

function generatePartyCode() : string {
    $alphabets="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $code = "";
    for($i = 0; $i < 6; $i++) {
        $code .= $alphabets[random_int(0, strlen($alphabets)-1)];
    }
    return $code;
}

/**
 * @throws HttpHeaderException
 * @throws InvalidArgumentException
 */
function joinParty(string $partyCode): bool {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM \"QuestKeeper\".party WHERE join_code=?");
    $stmt->execute([$partyCode]);
    $party = $stmt->fetch();
    if($party == null) throw new InvalidArgumentException("party does not exist");

    $user = getCurrentUser();

    // if user is mj
    if($party["master"] == $user->getId()) {
        return $party["id"];
    }

    // if user is player && has a selected char
    if($user->getPlayer() == null) throw new InvalidArgumentException("No player selected, can't join party without a player");
    $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".partyplayer(id_party, id_player) VALUES(?,?);");
    try {
        $stmt->execute([$party["id"],$user->getPlayer()->getId()]);
    } catch (PDOException $e) {
        if($e->getCode() == 23505) return $party["id"];
        else throw $e;
    }
    return $party["id"];
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