<?php

require_once "Stat.php";
require_once "Item.php";

class Player
{
    public string $id;
    public string $name;
    public string $desc;
    /** @var array<int, Stat> */
    public array $playerStats = [];
    /** @var array<int, Item> */
    public array $playerItems = [];


    public function __construct(array $player, array $playerItems, array $playerStats)
    {
        $this->id = $player["id"];
        $this->name = $player["name"];
        $this->desc = $player["desc"];
        foreach ($playerItems as $item) {
            $this->playerItems[] = new Item($item);
        }
        foreach ($playerStats as $stat) {
            $this->playerStats[] = new Stat($stat);
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
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @return mixed|string
     */
    public function getDesc(): mixed
    {
        return $this->desc;
    }

    /**
     * @return array
     */
    public function getPlayerStats(): array
    {
        return $this->playerStats;
    }

    /**
     * @return array
     */
    public function getPlayerItems(): array
    {
        return $this->playerItems;
    }


}