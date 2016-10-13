<?php

/**
 * CoolCoinsCommand.php Class
 *
 * Created on 30/05/2016 at 10:03 PM
 *
 * @author Jack
 */

namespace coolcoins\command;

use coolcoins\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

abstract class CoolCoinsCommand extends Command implements PluginIdentifiableCommand {

	/** @var Main */
	protected $plugin;

	/**
	 * CoolCoinsCommand constructor
	 *
	 * @param Main $plugin
	 * @param string $name
	 * @param null|string $description
	 * @param array|\string[] $usageMessage
	 * @param array $aliases
	 */
	public function __construct(Main $plugin, $name, $description, $usageMessage, $aliases) {
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * 
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		return $this->checkSender($sender, $args);
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 * 
	 * @return bool
	 */
	public abstract function checkSender(CommandSender $sender, array $args);

}