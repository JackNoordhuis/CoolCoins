<?php

/**
 * MySQLCreateCoinsSaveTask.php Class
 *
 * Created on 30/05/2016 at 10:20 PM
 *
 * @author Jack
 */


namespace coolcoins\provider\mysql\task;

use coolcoins\Main;
use coolcoins\provider\mysql\MySQLProvider;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class MySQLCreateCoinsSaveTask extends MySQLTask {

	/** @var string */
	protected $player;

	/** @var int */
	protected $coins;

	/**
	 * MySQLCreateCoinsSaveTask constructor
	 *
	 * @param MySQLProvider $provider
	 * @param string $player
	 * @param int $coins
	 */
	public function __construct(MySQLProvider $provider, $player, $coins = 0) {
		parent::__construct($provider->getCredentials());
		$this->player = strtolower($player);
		$this->coins = $coins;
	}

	/**
	 * Create a place to save the coins
	 */
	public function onRun() {
		$mysqli = $this->getMysqli();
		$mysqli->query("INSERT INTO coolcoins (username, coins) VALUES
			('{$mysqli->escape_string($this->player)}', {$this->coins})");
		$mysqli->close();
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server) {
		$coolCoins = $server->getPluginManager()->getPlugin("CoolCoins");
		$player = $server->getPlayer($this->player);
		if($coolCoins instanceof Main) {
			$coolCoins->getLogger()->debug(TextFormat::YELLOW . "Created save for " . $this->player);
			if($player instanceof Player) {
				$coolCoins->startCoinHolder($player, $this->coins);
			}
		}
	}

}