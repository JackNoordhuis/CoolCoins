<?php

/**
 * MySQLDeleteCoinsSaveTask.php Class
 *
 * Created on 30/05/2016 at 10:25 PM
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
		$stmt = $mysqli->stmt_init();
		$stmt->prepare("DELETE FROM coolcoins WHERE username = ?");
		$stmt->bind_param("s", $mysqli->escape_string($this->player));
		$stmt->execute();
		if($this->checkError($stmt)) return;
		if($this->checkAffectedRows($stmt)) return;
		$this->setResult(self::SUCCESS);
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
						$server->getLogger()->debug("Failed to complete DeleteCoinsSaveTask for coolcoins database due to a connection error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
					case self::SUCCESS:
						$plugin->closeCoinHolder($player, false);
						$server->getLogger()->debug("Successfully completed DeleteCoinsSaveTask for coolcoins database! User: {$this->player}");
						return;
					case self::NO_DATA:
					case self::NO_CHANGE:
						$server->getLogger()->debug("Failed to complete DeleteCoinsSaveTask for coolcoins database due the username not being registered! User: {$this->player}");
						return;
					case self::MYSQLI_ERROR:
						$server->getLogger()->debug("Failed to complete DeleteCoinsSaveTask for coolcoins database due to a mysqli error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
				}
			} else {
				$server->getLogger()->debug("Failed to complete DeleteCoinsSaveTask for coolcoins database due to user not being online! User: {$this->player}");
				return;
			}
		} else {
			$server->getLogger()->debug("Attempted to complete DeleteCoinsSaveTask for coolcoins database while CoolCoins plugin isn't enabled! User: {$this->player}");
			throw new PluginException("CoolCoins plugin isn't enabled!");
		}
	}

}