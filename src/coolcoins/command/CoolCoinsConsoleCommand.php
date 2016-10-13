<?php

namespace coolcoins\command;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

abstract class CoolCoinsConsoleCommand extends CoolCoinsCommand {

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public function checkSender(CommandSender $sender, array $args) {
		if($sender instanceof ConsoleCommandSender) {
			return $this->onCommand($sender, $args);
		} else {
			$sender->sendMessage("Please run this command via console!");
			return true;
		}
	}

	/**
	 * @param ConsoleCommandSender $console
	 * @param array $args
	 *
	 * @return bool
	 */
	public abstract function onCommand(ConsoleCommandSender $console, array $args);

}