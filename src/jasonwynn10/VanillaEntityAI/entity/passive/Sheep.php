<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

use jasonwynn10\VanillaEntityAI\data\ColorToMeta;
use jasonwynn10\VanillaEntityAI\entity\AnimalBase;
use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\Interactable;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\item\Dye;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Shears;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class Sheep extends AnimalBase implements Collidable, Interactable {
	public const NETWORK_ID = self::SHEEP;
	public $width = 1.2;
	public $height = 0.6;
	private $colorMeta = ColorToMeta::WHITE;
	private $sheared = false;

	public function initEntity() : void {
		$this->setMaxHealth(8);
		parent::initEntity();

		if((bool)$this->namedtag->getByte("Sheared", 0)) {
			$this->setSheared(true);
		}else {
			$this->setSheared(false);
		}

		$chance = mt_rand(1, 1000);
		if($chance <= 50) {
			$colorMeta = ColorToMeta::LIGHT_GRAY;
		}elseif($chance >= 51 and $chance <= 100) {
			$colorMeta = ColorToMeta::GRAY;
		}elseif($chance >= 101 and $chance <= 150) {
			$colorMeta = ColorToMeta::BLACK;
		}elseif($chance >= 151 and $chance <= 180) {
			$colorMeta = ColorToMeta::BROWN;
		}elseif($chance >= 181 and $chance <= 183) {
			$colorMeta = ColorToMeta::PINK;
		}else {
			$colorMeta = ColorToMeta::WHITE;
		}
		if($this->namedtag->getByte("Color", ColorToMeta::WHITE) !== null)
			$colorMeta = $this->namedtag->getByte("Color", ColorToMeta::WHITE);
		$this->setColor($colorMeta);
		if(mt_rand(1, 100) <= 5) {
			$this->setBaby(true);
		}
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		// TODO: eat grass to recover wool
		// TODO: Sheep follow players holding wheat within 8 blocks
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}

	/**
	 * @return array
	 */
	public function getDrops() : array {
		$drops = parent::getDrops();
		if(!$this->isBaby()) {
			if($this->isOnFire()) {
				$drops[] = ItemFactory::get(Item::COOKED_MUTTON, 0, mt_rand(1, 3));
			}else {
				$drops[] = ItemFactory::get(Item::MUTTON, 0, mt_rand(1, 3));
			}
			if($this->isSheared()) {
				return $drops;
			}
			$drops[] = ItemFactory::get(Item::WOOL, $this->colorMeta);
			return $drops;
		}else {
			return $drops;
		}
	}

	public function getXpDropAmount() : int {
		if(!$this->isBaby()) {
			return mt_rand(1, 3);
		}
		return parent::getXpDropAmount();
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Sheep";
	}

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
		// TODO: Implement onCollideWithEntity() method.
	}

	public function onPlayerLook(Player $player) : void {
		$hand = $player->getInventory()->getItemInHand();
		if(!$this->isBaby() and $hand instanceof Shears and !$this->sheared) {
			$this->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, "Shear");
		}
		if($hand instanceof Dye and !$this->sheared) {
			$this->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, "Dye");
		}
	}

	public function onPlayerInteract(Player $player) : void {
		$hand = $player->getInventory()->getItemInHand();
		if(!$this->isBaby() and $hand instanceof Shears and !$this->sheared) {
			$this->shear();
			$hand->applyDamage(1);
			$player->getInventory()->setItemInHand($hand);
			$this->level->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_SHEAR, 0, EntityIds::PLAYER);
		}
		if($hand instanceof Dye and !$this->sheared) {
			$this->setColor($hand->pop()->getDamage());
			$player->getInventory()->setItemInHand($hand);
		}
	}

	/**
	 * @return Sheep
	 */
	public function shear() : self {
		$this->level->dropItem($this, ItemFactory::get(Item::WOOL, $this->colorMeta, mt_rand(1, 3)));
		$this->setSheared(true);
		return $this;
	}

	/**
	 * @param bool $sheared
	 *
	 * @return Sheep
	 */
	public function setSheared(bool $sheared = true) : self {
		$this->sheared = $sheared;
		$this->setGenericFlag(self::DATA_FLAG_SHEARED, $sheared);
		$this->namedtag->setByte("Sheared", (int)$sheared);
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSheared() : bool {
		return $this->sheared;
	}

	/**
	 * @param int $colorMeta
	 *
	 * @return Sheep
	 */
	public function setColor(int $colorMeta) : self {
		if($colorMeta >= 0 and $colorMeta <= 15) {
			$this->colorMeta = $colorMeta;
			$this->getDataPropertyManager()->setPropertyValue(self::DATA_COLOUR, self::DATA_TYPE_BYTE, $colorMeta);
			$this->namedtag->setByte("Color", $colorMeta);
		}else {
			throw new \OutOfRangeException("Meta value provided is out of range 0 - 15");
		}
		return $this;
	}

	/**
	 * @return int
	 */
	public function getColor() : int {
		return $this->colorMeta;
	}
}