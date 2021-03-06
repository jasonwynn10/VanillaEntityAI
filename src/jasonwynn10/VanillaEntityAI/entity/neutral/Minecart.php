<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\neutral;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use jasonwynn10\VanillaEntityAI\entity\Interactable;
use jasonwynn10\VanillaEntityAI\entity\Linkable;
use jasonwynn10\VanillaEntityAI\entity\LinkableTrait;
use jasonwynn10\VanillaEntityAI\entity\Lookable;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\AxisAlignedBB;

class Minecart extends Entity implements Interactable, Linkable, Lookable, Collidable {
	use LinkableTrait, CollisionCheckingTrait;
	/** @var CreatureBase $link */
	private $link;

	public function initEntity() : void {
		parent::initEntity(); // TODO: Change the autogenerated stub
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Minecart";
	}

	public function onPlayerInteract(Player $player) : void {
		// TODO: Implement onPlayerInteract() method.
	}

	/**
	 * @param Player $player
	 */
	public function onPlayerLook(Player $player) : void {
		$this->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, "Ride");
	}

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
		// TODO: Implement onCollideWithEntity() method.
	}

	/**
	 * @param Block $block
	 */
	public function onCollideWithBlock(Block $block) : void {
		// TODO: Implement onCollideWithBlock() method.
	}

	/**
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source) : void {
		// TODO: Implement push() method.
	}
}