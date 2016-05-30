<?php

/**
 * MySQLLoadCoinsTask.php Class
 *
 * Created on 30/05/2016 at 7:43 PM
 *
 * @author Jack
 */

namespace coolcoins\provider\mysql\task;

use coolcoins\Main;
use coolcoins\provider\mysql\MySQLProvider;
use pocketmine\Player;
use pocketmine\Server;

/**
 * Load a players coins into a coin holder
 */
class MySQLLoadCoinsTask extends MySQLTask {

	/** @var string */
	protected $player;

	/**
	 * MySQLLoadCoinsTask constructor
	 *
	 * @param MySQLProvider $provider
	 * @param string $player
	 */
	public function __construct(MySQLProvider $provider, $player) {
		parent::__construct($provider->getCredentials());
		$this->player = strtolower($player);
	}

	/**
	 * Get a players coins
	 */
	public function onRun() {
		$mysqli = $this->getMysqli();
		$result = $mysqli->query("SELECT * FROM coolcoins WHERE username = '{$mysqli->escape_string($this->player)}'");
		if($result instanceof \mysqli_result) {
			$row = $result->fetch_assoc();
			$result->free();
			$mysqli->close();
			if(is_array($row)) {
				$this->setResult($row);
			} else {
				$this->setResult(false);
			}
		} else {
			$this->setResult(false);
		}
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server) {
		$plugin = $server->getPluginManager()->getPlugin("CoolCoins");
		$player = $server->getPlayer($this->player);
		if($plugin instanceof Main) {
			if($player instanceof Player) {
				if($this->getResult() !== false) {
					$plugin->startCoinHolder($player, $this->getResult()["coins"]);
				} else {
					$plugin->getProvider()->createSave($player->getName(), (int)$plugin->settings["basic-settings"]["starting-coins"]);
				}
			}
		}
	}

}