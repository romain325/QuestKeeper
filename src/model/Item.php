<?php

class Item
{
    public string $name;
    public string $id;
    public string $description;

    public function __construct(array $data) {
        $this->name = $data["name"];
        $this->id = $data["id"];
        $this->description = $data["description"];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


}