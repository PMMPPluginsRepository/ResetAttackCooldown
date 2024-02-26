<?php

declare(strict_types=1);

namespace skh6075\resetattackcooldown;

use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\EventPriority;
use pocketmine\plugin\PluginBase;
use ReflectionProperty;

final class Main extends PluginBase{
	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(EntityDamageEvent::class, function (EntityDamageEvent $event): void{
			static $attackTimeProperty = null;
			if($attackTimeProperty === null){
				$attackTimeProperty = new ReflectionProperty(Living::class, "attackTime");
			}

			$entity = $event->getEntity();
			if($entity->isAlive()){
				$entity->noDamageTicks = 0;
				if($entity instanceof Living){
					$attackTimeProperty->setValue($entity, 0);
				}
			}
			if($event instanceof EntityDamageByEntityEvent){
				$attacker = $event->getDamager();
				if($attacker instanceof Living){
					$attacker->noDamageTicks = 0;
					$attackTimeProperty->setValue($attacker, 0);
				}
			}
			$event->setAttackCooldown(-1);
		}, EventPriority::HIGHEST, $this);
	}
}