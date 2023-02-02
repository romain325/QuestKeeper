<?php

require_once $_SERVER["DOCUMENT_ROOT"]."src/service/PlayerService.php";

class User
{
    public string $username;
    public string $id;
    private string $password;
    public Player|null $player = null;
    public function __construct(array $dbData)
    {
        if($dbData == null || $dbData["id"] == null){
            throw new InvalidArgumentException("user not found");
        }
        $this->username = $dbData["name"];
        $this->id = $dbData["id"];
        $this->password = $dbData["password_hash"];

        //WARN should use a factory because that looks disgusting
        if($dbData["current_avatar"] != null) {
            $this->player = getPlayerById($dbData["current_avatar"]);
        }
    }

    /**
     * @return mixed|string
     */
    public function getId(): mixed
    {
        return $this->id;
    }
    /**
     * @return mixed|string
     */
    public function getUsername(): mixed
    {
        return $this->username;
    }

    /**
     * @return mixed|string
     */
    public function getPassword(): mixed
    {
        return $this->password;
    }

    /**
     * @return Player|null
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }


}