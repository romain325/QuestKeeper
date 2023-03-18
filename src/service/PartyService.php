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
function joinParty(string $partyCode): string {
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

function getPartyPlayers(string $partyId) : array {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT id_player FROM \"QuestKeeper\".partyplayer WHERE id_party=?");
    $stmt->execute([$partyId]);
    $result = array();
    while($row = $stmt->fetch()) {
        $result[] = getPlayerById($row["id_player"]);
    }
    return $result;
}

/**
 * @throws InvalidArgumentException, PDOException
 */
function getPartyMaster(string|null $code, string|null $id) : string {
    $pdo = PDOService::getPDO();
    if($code != null){
        $stmt = $pdo->prepare("SELECT master FROM \"QuestKeeper\".party WHERE join_code=?");
        $stmt->execute([$code]);
        return $stmt->fetch()["master"];
    } else if($id != null) {
        $stmt = $pdo->prepare("SELECT master FROM \"QuestKeeper\".party WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch()["master"];
    } else {
        throw new InvalidArgumentException("missing either id or code");
    }
}

function getPartyItems(string $id) : array {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("SELECT i.* FROM \"QuestKeeper\".partyitems
                                    INNER JOIN \"QuestKeeper\".item i on i.id = partyitems.id_item
                                    WHERE id_party=?");
    $stmt->execute([$id]);
    $res = [];
    while($item = $stmt->fetch()) {
        $res[] = new Item($item);
    }
    return $res;
}

function addPartyItems(string $id, array $items) {
    $pdo = PDOService::getPDO();
    foreach($items as $item) {
        try{
            $stmt = $pdo->prepare("INSERT INTO \"QuestKeeper\".partyitems(id_party,id_item) VALUES(?,?)");
            $stmt->execute([$id, $item]);
        } catch (PDOException $e) {
            continue;
        }
    }
}

/**
 * @param string $id
 * @return void
 * @throws Exception
 */
function deleteParty(string $id) {
    if(getPartyMaster(null, $id) != getCurrentUser()->getId()) {
        throw new Exception("You're not the master of this party");
    }

    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".partyitems WHERE id_party=?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".partyplayer WHERE id_party=?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".party WHERE id=?");
    $stmt->execute([$id]);
}

function removePlayerFromParty(string $party, string $player) : void {
    $pdo = PDOService::getPDO();
    $stmt = $pdo->prepare("DELETE FROM \"QuestKeeper\".partyplayer WHERE id_party=? AND id_player=?;");
    $stmt->execute([$party, $player]);
}