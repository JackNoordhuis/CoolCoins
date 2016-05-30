<?php

/**
 * MySQLDeleteCoinsSaveTask.php Class
 *
 * Created on 30/05/2016 at 10:25 PM
 *
 * @author Jack
 */

namespace coolcoins\provider\mysql\task;

use coolcoins\provider\mysql\MySQLProvider;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class MySQLDeleteCoinsSaveTask extends MySQLTask {

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
		$mysqli->query("DELETE FROM coolcoins WHERE username = '{$mysqli->escape_string($this->player)}'");
		$mysqli->close();
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server) {
		$server->getLogger()->debug(TextFormat::YELLOW . "Deleted coins save for {$this->player}");
	}

}