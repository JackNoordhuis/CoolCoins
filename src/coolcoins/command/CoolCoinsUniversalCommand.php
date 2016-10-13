<?php

namespace coolcoins\command;

use pocketmine\command\CommandSender;

abstract class CoolCoinsUniversalCommand extends CoolCoinsCommand {

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public function checkSender(CommandSender $sender, array $args) {
		return $this->onCommand($sender, $args);
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public abstract function onCommand(CommandSender $sender, array $args);

}