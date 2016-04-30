<?php

namespace App\Libraries\Game\TrinityCore\Item;

/**
* Item classes and subclasses
*/
class ItemClass
{
	protected $item;

	protected $classes = [
    	0	=>	'Consumable',
    	1	=>	'Container',
    	2	=>	'Weapon',
    	3	=>	'Gem',
    	4	=>	'Armor',
    	5	=>	'Reagent',
    	6	=>	'Projectile',
    	7	=>	'Trade Goods',
    	8	=>	'Generic (Obsolete)',
    	9	=>	'Recipe',
    	10	=>	'Money',
    	11	=>	'Quiver',
    	12	=>	'Quest',
    	13	=>	'Key',
    	14	=>	'Permanent (Obsolete)',
    	15	=>	'Glyph'
    ];	

	public function __construct(Item $item)
	{
		$this->item = $item;
	}

	public function getClass() : string 
    {
    	$item = $this->item->class;
    	$classes = $this->classes;
    	if(array_key_exists($item, $classes))
    	{
    		return $classes[$item];
    	} 

    	return 'unknown';
    }

    public function getSubclass() : string
    {
    	throw new \Exception("Not yet implemented!", 500);
    	
    	//return (new ItemSubClass($this->item))->getSubclass();
    }
}