<?php

/**
 * SetCoinsCommand.php Class
 *
 * Created on 30/05/2016 at 10:36 PM
 *
 * @author Jack
 */


namespace coolcoins\command;

use coolcoins\Main;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SetCoinsCommand extends CoolCoinsCommand {

	public function __construct(Main $plugin) {
		parent::__construct($plugin, "setcoins", "Set a players coin balance", "/setcoins {name} {amount}", ["setbal", "setmoney", "setbalance"]);
	}

	public function onCommand(CommandSender $sender, array $args) {
		if(isset($args[1])) {
			$name = (string)$args[0];
			$coins = (int)$args[1];
			if(!is_string($name) or !is_int($coins)) {
				$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
				return true;
			}
			$this->getPlugin()->getProvider()->updateSave($name, (int)$coins);
			return true;
		} else {
			$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
			return true;
		}
	}
}