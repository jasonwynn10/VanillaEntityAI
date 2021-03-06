<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

use jasonwynn10\VanillaEntityAI\entity\AnimalBase;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class Chicken extends AnimalBase {
	public const NETWORK_ID = self::CHICKEN;
	public $width = 1;
	public $height = 0.8;

	public function initEntity() : void {
		$this->setMaxHealth(4);
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
		// TODO: spawn egg item every 5-10 mins
		// TODO: follow seeds
	}

	/**
	 * @return Item[]
	 */
	public function getDrops() : array {
		$drops = parent::getDrops();
		if(!$this->isBaby()) {
			if($this->isOnFire()) {
				$drops[] = ItemFactory::get(Item::COOKED_CHICKEN, 0, mt_rand(1, 3));
			}else{
				$drops[] = ItemFactory::get(Item::CHICKEN, 0, mt_rand(1, 3));
			}
		}
		$drops[] = ItemFactory::get(Item::FEATHER, 0, mt_rand(0, 2));
		return $drops;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount() : int {
		$exp = parent::getXpDropAmount();
		if(!$this->isBaby()) {
			$exp += mt_rand(1, 3);
			return $exp;
		}
		return $exp;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Chicken";
	}
}