import type Stat from "@/models/Stat";
import type Item from "@/models/Item";

export default interface Player {
    id: string,
    name: string,
    desc: string,
    playerStats: Stat[],
    playerItems: Item[],
}