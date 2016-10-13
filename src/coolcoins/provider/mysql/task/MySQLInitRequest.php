<?php


namespace coolcoins\provider\mysql\task;


use coolcoins\Main;
use coolcoins\provider\mysql\MySQLProvider;
use coolcoins\provider\mysql\MySQLTask;
use pocketmine\Server;
use pocketmine\utils\PluginException;

/**
 * Reqest to verify the database is online and the table exists
 */
class MySQLInitRequest extends MySQLTask {

	/**
	 * MySQLInitRequest constructor
	 *
	 * @param MySQLProvider $provider
	 */
	public function __construct(MySQLProvider $provider) {
		parent::__construct($provider->getCredentials());
	}

	public function onRun() {
		$mysqli = $this->getMysqli();
		if($this->checkConnection($mysqli)) return;
		$mysqli->query("CREATE TABLE IF NOT EXISTS coolcoins (
			username VARCHAR(63) PRIMARY KEY,
			coins INT
		)");
		if($this->checkError($mysqli)) return;
		$this->setResult(MySQLTask::SUCCESS);
		$mysqli->close();
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server) {
		$plugin = $server->getPluginManager()->getPlugin("CoolCoins");
		if($plugin instanceof Main and $plugin->isEnabled()) {
			$result = $this->getResult();
			switch((is_array($result) ? $result[0] : $result)) {
				case self::SUCCESS:
					$plugin->getLogger()->debug("Successfully completed MySQLInitRequest on coolcoins database!");
					return;
				case self::CONNECTION_ERROR:
					$plugin->getLogger()->critical("Couldn't connect to kingdomscraft_database! Error: {$result[1]}");
					$plugin->getLogger()->debug("Connection error while executing MySQLInitRequest on coolcoins database!");
					return;
				case self::MYSQLI_ERROR:
					$plugin->getLogger()->error("MySQL error while querying coolcoins database! Error: {$result[1]}");
					$plugin->getLogger()->debug("MySQL error while executing MySQLInitRequest on coolcoins database!");
					return;
			}
		} else {
			throw new PluginException("Attempted to execute MySQLInitRequest while CoolCoins plugin isn't loaded!");
		}
	}

}