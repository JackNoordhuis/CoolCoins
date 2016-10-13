<?php


namespace coolcoins\command;


use coolcoins\Main;

class CoolCoinsCommandMap {

	/** @var Main */
	private $plugin;

	/** @var CoolCoinsCommand[] */
	protected $commands = [];

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		$this->setDefaultCommands();
	}
	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}
	/**
	 * Set the default commands
	 */
	public function setDefaultCommands() {
		$this->registerAll([

		]);
	}
	/**
	 * Register an array of commands
	 *
	 * @param array $commands
	 */
	public function registerAll(array $commands) {
		foreach($commands as $command) {
			$this->register($command);
		}
	}
	/**
	 * Register a command
	 *
	 * @param CoolCoinsCommand $command
	 * @param string $fallbackPrefix
	 */
	public function register(CoolCoinsCommand $command, $fallbackPrefix = "kc") {
		$this->plugin->getServer()->getCommandMap()->register($fallbackPrefix, $command);
		$this->commands[strtolower($command->getName())] = $command;
	}

	/**
	 * Unregisters all commands
	 */
	public function clearCommands() {
		foreach($this->commands as $command) {
			$this->unregister($command);
		}
		$this->commands = [];
		$this->setDefaultCommands();
	}

	/**
	 * Unregister a command
	 *
	 * @param CoolCoinsCommand $command
	 */
	public function unregister(CoolCoinsCommand $command) {
		$command->unregister($this->plugin->getServer()->getCommandMap());
		unset($this->commands[strtolower($command->getName())]);
	}

	/**
	 * Get a command
	 *
	 * @param $name
	 *
	 * @return CoolCoinsCommand
	 */
	public function getCommand($name) {
		if(isset($this->commands[$name])) {
			return $this->commands[$name];
		}
		return null;
	}

	/**
	 * @return CoolCoinsCommand[]
	 */
	public function getCommands() {
		return $this->commands;
	}

	public function __destruct() {
		$this->close();
	}

	public function close() {
		foreach($this->commands as $command) {
			$this->unregister($command);
		}
		unset($this->commands, $this->plugin);
	}

}