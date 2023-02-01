<?php

class User
{
    private string $username;
    private string $id;
    private string $password;
    public function __construct(array $dbData)
    {
        if($dbData == null || $dbData["id"] == null){
            throw new InvalidArgumentException("user not found");
        }
        $this->username = $dbData["name"];
        $this->id = $dbData["id"];
        $this->password = $dbData["password_hash"];
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
}