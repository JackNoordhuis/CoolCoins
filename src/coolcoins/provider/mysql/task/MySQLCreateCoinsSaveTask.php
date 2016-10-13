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
use coolcoins\provider\mysql\MySQLTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\PluginException;
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
		$stmt = $mysqli->stmt_init();
		$stmt->prepare("INSERT INTO coolcoins (username, coins) VALUES (?, ?)");
		$stmt->bind_param("si", $mysqli->escape_string($this->player), $this->coins);
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
						$server->getLogger()->debug("Failed to complete CreateCoinsSaveTask for coolcoins database due to a connection error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
					case self::SUCCESS:
						$plugin->startCoinHolder($player, ($this->coins <= 0 ? $plugin->getSettings()->getNested("basic-settings.starting-coins") : $this->coins));
						$server->getLogger()->debug("Successfully completed CreateCoinsSaveTask for coolcoins database! User: {$this->player}");
						return;
					case self::NO_DATA:
					case self::NO_CHANGE:
						$server->getLogger()->debug("Failed to complete CreateCoinsSaveTask for coolcoins database due the username not being registered! User: {$this->player}");
						return;
					case self::MYSQLI_ERROR:
						$server->getLogger()->debug("Failed to complete CreateCoinsSaveTask for coolcoins database due to a mysqli error. Error: {$result[1]}");
						throw new \RuntimeException($result[1]);
				}
			} else {
				$server->getLogger()->debug("Failed to complete CreateCoinsSaveTask for coolcoins database due to user not being online! User: {$this->player}");
				return;
			}
		} else {
			$server->getLogger()->debug("Attempted to complete CreateCoinsSaveTask for coolcoins database while CoolCoins plugin isn't enabled! User: {$this->player}");
			throw new PluginException("CoolCoins plugin isn't enabled!");
		}
	}

}