<?php

/**
 * GetCoinsCommand.php Class
 *
 * Created on 30/05/2016 at 10:07 PM
 *
 * @author Jack
 */


namespace coolcoins\command;

use coolcoins\CoinHolder;
use coolcoins\Main;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GetCoinsCommand extends CoolCoinsUniversalCommand {

	public function __construct(Main $plugin) {
		parent::__construct($plugin, "getcoins", "Get a players coin balance", "/getcoins {name}", ["coins", "viewcoins", "money", "balance", "mymoney"]);
	}

	public function onCommand(CommandSender $sender, array $args) {
		if(isset($args[0])) {
			$target = $this->getPlugin()->getServer()->getPlayer($args[0]);
			if($target instanceof Player) {
				$coinHolder = $this->getPlugin()->getCoinHolder($target);
				if($coinHolder instanceof CoinHolder) {
					$sender->sendMessage(TextFormat::GOLD . $target->getName() . TextFormat::AQUA . " has" . TextFormat::GREEN . " $" . $coinHolder->getCoins() . TextFormat::RESET);
					return true;
				}
			}

			$this->getPlugin()->getProvider()->viewCoins($args[0], $sender);
		}
		$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
		return true;
	}

}
