<?php

declare(strict_types=1);

namespace nick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use function strtolower;

class Nick extends PluginBase implements Listener {

	private static array $database;

	public function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		self::$database = (new Config($this->getDataFolder() . 'database.yml', Config::YAML))->getAll();
	}

	public function onDisable(): void {
		$database = new Config($this->getDataFolder() . 'database.yml', Config::YAML);
		$database->setAll(self::$database);
		$database->save();
	}

	public function onJoin(PlayerJoinEvent $ev) {
		$player = $ev->getPlayer();
		if(!isset(self::$database[strtolower($player->getName())])) {
			self::$database[strtolower($player->getName())] = $player->getName();
		}
	}

	public static function getNick(string $name): string {
		return self::$database[strtolower($name)] ?? strtolower($name);
	}
}