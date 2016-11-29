<?php

/**
 * SetCoinsCommand.php Class
 *
 * Created on 30/05/2016 at 10:36 PM
 *
 * @author Jack
 */

namespace coolcoins\command;

use coolcoins\CoinHolder;
use coolcoins\Main;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetCoinsCommand extends CoolCoinsUniversalCommand {

	public function __construct(Main $plugin) {
		parent::__construct($plugin, "setcoins", "Set a players coin balance", "/setcoins {name} {amount}", ["setbal", "setmoney", "setbalance"]);
	}

	public function onCommand(CommandSender $sender, array $args) {
		if(isset($args[1])) {
			$name = (string)$args[0];
			$coins = (int)$args[1];
			$target = $this->getPlugin()->getServer()->getPlayer($name);
			if($target instanceof Player) {
				$holder = $this->getPlugin()->getCoinHolder($target);
				if($holder instanceof CoinHolder) {
					$sender->sendMessage("Coins: {$holder->getCoins()}");
				}
			}

		}
		$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
		return true;
	}
}
