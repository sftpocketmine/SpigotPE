<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\block;

use pocketmine\Player;
use pocketmine\inventory\ShulkerBoxInventory;
//use pocketmine\inventory\EnderChestInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\ShulkerBox as tiletest;
use pocketmine\tile\Tile;

class ShulkerBox extends Solid{
	
	protected $id = self::SHULKER_BOX;
	
    public function __construct($meta = 0){
        $this->meta = $meta;
    }
	
    public function canBeActivated(){
        return true;
    }
	
    public function getHardness(){
        return 22.5;
    }
	
    public function getResistance(){
        return 3000;
    }
	
    public function getToolType(){
        return Tool::TYPE_PICKAXE;
    }
	
	public function getName(){
		static $names = [
			0 => "White Shulker Box",
			1 => "Orange Shulker Box",
			2 => "Magenta Shulker Box",
			3 => "Light Blue Shulker Box",
			4 => "Yellow Shulker Box",
			5 => "Lime Shulker Box",
			6 => "Pink Shulker Box",
			7 => "Gray Shulker Box",
			8 => "Silver Shulker Box",
			9 => "Cyan Shulker Box",
			10 => "Purple Shulker Box",
			11 => "Blue Shulker Box",
			12 => "Brown Shulker Box",
			13 => "Green Shulker Box",
			14 => "Red Shulker Box",
			15 => "Black Shulker Box",
		];
		return $names[$this->meta & 0x0f];
		
        $this->getLevel()->setBlock($block, $this, true, true);
        $nbt = new CompoundTag("", [
            new StringTag("id", Tile::SHULKER_BOX),
            new IntTag("x", $this->x),
            new IntTag("y", $this->y),
            new IntTag("z", $this->z)
        ]);
		
        if($item->hasCustomName()){
            $nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
        }
		
        Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), $nbt);
        return true;
		
    }
    public function onActivate(Item $item, Player $player = null){
        if($player instanceof Player){
            $top = $this->getSide(1);
            if($top->isTransparent() !== true){
                return true;
            }
			
            $t = $this->getLevel()->getTile($this);
            $chest = null;
            if($t instanceof tiletest){
                $chest = $t;
            }else{
                $nbt = new CompoundTag("", [
                    new StringTag("id", Tile::SHULKER_BOX),
                    new IntTag("x", $this->x),
                    new IntTag("y", $this->y),
                    new IntTag("z", $this->z)
                ]);
                $chest = Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), $nbt);
            }
			
            if($chest instanceof tiletest){
                $player->addWindow(new ShulkerBoxInventory($this, $player));
				
            }
        }
        return true;
    }
	
    public function getDrops(Item $item){
        if ($item->hasEnchantments() && $item->getEnchantment(Enchantment::TYPE_MINING_SILK_TOUCH) !== null){
            return [
                [$this->id, 0, 1],
            ];
        }
		
        return [
            [Item::dirt, 0, 8], //dubug
        ];
    }
}
