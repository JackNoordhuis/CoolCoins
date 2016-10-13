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
use coolcoins\provider\mysql\MySQLTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\PluginException;
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
		$stmt = $mysqli->stmt_init();
		$stmt->prepare("UPDATE coolcoins SET coins = ? WHERE username = ?");
		$stmt->bind_param("is", $this->coins, $mysqli->escape_string($this->player));
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
						$server->getLogger()->debug("Failed to complete UpdateCoinsTask for coolcoins database due to a connection error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
					case self::SUCCESS:
						$server->getLogger()->debug("Successfully completed UpdateCoinsTask for coolcoins database! User: {$this->player}");
						return;
					case self::NO_DATA:
					case self::NO_CHANGE:
						$plugin->getProvider()->createSave($player, $this->coins);
						$server->getLogger()->debug("Failed to complete UpdateCoinsTask for coolcoins database due the username not being registered! User: {$this->player}");
						return;
					case self::MYSQLI_ERROR:
						$server->getLogger()->debug("Failed to complete UpdateCoinsTask for coolcoins database due to a mysqli error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
				}
			} else {
				$server->getLogger()->debug("Failed to complete UpdateCoinsTask for coolcoins database due to user not being online! User: {$this->player}");
				return;
			}
		} else {
			$server->getLogger()->debug("Attempted to complete UpdateCoinsTask for coolcoins database while CoolCoins plugin isn't enabled! User: {$this->player}");
			throw new PluginException("CoolCoins plugin isn't enabled!");
		}
	}

}