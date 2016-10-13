<?php

namespace coolcoins\command;

use pocketmine\command\CommandSender;
use pocketmine\Player;

abstract class CoolCoinsPlayerCommand extends CoolCoinsCommand {

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public function checkSender(CommandSender $sender, array $args) {
		if($sender instanceof Player) {
			return $this->onCommand($sender, $args);
		} else {
			$sender->sendMessage("Please run this command in-game!");
			return true;
		}
	}

	/**
	 * @param Player $player
	 * @param array $args
	 *
	 * @return bool
	 */
	public abstract function onCommand(Player $player, array $args);

}