<?php

/**
 * MySQLUpdateCoinsTask.php Class
 *
 * Created on 30/05/2016 at 8:16 PM
 *
 * @author Jack
 */


namespace coolcoins\provider\mysql\task;

use coolcoins\Main;
use coolcoins\provider\mysql\MySQLProvider;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

/**
 * Update a players coins
 */
class MySQLUpdateCoinsTask extends MySQLTask {

	/** @var string */
	protected $player;

	/** @var int */
	protected $coins;

	/**
	 * MySQLUpdateCoinsTask constructor
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
	 * Save a players coins
	 */
	public function onRun() {
		$mysqli = $this->getMysqli();
		$mysqli->query("UPDATE coolcoins SET coins = {$this->coins} WHERE username = {$mysqli->escape_string($this->player)}");
		if(count($mysqli->affected_rows) > 0) {
			$this->setResult(false);
		}
		$mysqli->close();
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server) {
		$coolCoins = $server->getPluginManager()->getPlugin("CoolCoins");
		$player = $server->getPlayer($this->player);
		if($coolCoins instanceof Main) {
			if($this->getResult() !== false) {
				$coolCoins->getLogger()->debug(TextFormat::YELLOW . "Saved coins for " . $this->player);
				if($player instanceof Player) {
					$coolCoins->updateCoinHolder($player, $this->coins);
				}
			} else {
				$coolCoins->getProvider()->createSave($this->player, $this->coins);
			}
		}
	}

}