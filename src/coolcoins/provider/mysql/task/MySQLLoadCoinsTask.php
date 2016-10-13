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
use coolcoins\provider\mysql\MySQLTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\PluginException;

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
		$stmt = $mysqli->stmt_init();
		$stmt->prepare("SELECT * FROM coolcoins WHERE username = ?");
		$stmt->bind_param("s", $mysqli->escape_string($this->player));
		$stmt->execute();
		$result = $stmt->get_result();
		if($this->checkError($stmt)) return;
		$row = $result->fetch_assoc();
		$result->free();
		if($this->checkResult($row)) return;
		$this->setResult([self::SUCCESS, $row]);
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server) {
		$plugin = $this->getPlugin($server);
		if($plugin instanceof Main and $plugin->isEnabled()) {
			$player = $server->getPlayerExact($this->player);
			if($player instanceof Player) {
				$result = $this->getResult();
				switch((is_array($result) ? $result[0] : $result)) {
					case self::CONNECTION_ERROR:
						$server->getLogger()->debug("Failed to complete LoadCoinsTask for coolcoins database due to a connection error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
					case self::SUCCESS:
						$plugin->startCoinHolder($player, $result[1]["coins"]);
						$server->getLogger()->debug("Successfully completed LoadCoinsTask for coolcoins database! User: {$this->player}");
						return;
					case self::NO_DATA:
					case self::NO_CHANGE:
						$plugin->getProvider()->createSave($player);
						$server->getLogger()->debug("Failed to complete LoadCoinsTask for coolcoins database due the username not being registered! User: {$this->player}");
						return;
					case self::MYSQLI_ERROR:
						$server->getLogger()->debug("Failed to complete LoadCoinsTask for coolcoins database due to a mysqli error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
				}
			} else {
				$server->getLogger()->debug("Failed to complete LoadCoinsTask for coolcoins database due to user not being online! User: {$this->player}");
				return;
			}
		} else {
			$server->getLogger()->debug("Attempted to complete LoadCoinsTask for coolcoins database while CoolCoins plugin isn't enabled! User: {$this->player}");
			throw new PluginException("CoolCoins plugin isn't enabled!");
		}
	}

}