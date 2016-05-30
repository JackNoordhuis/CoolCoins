<?php

/**
 * MySQLProvider.php Class
 *
 * Created on 29/05/2016 at 7:49 PM
 *
 * @author Jack
 */

namespace coolcoins\provider\mysql;

use coolcoins\provider\DummyProvider;
use coolcoins\provider\mysql\task\MySQLCreateCoinsSaveTask;
use coolcoins\provider\mysql\task\MySQLDeleteCoinsSaveTask;
use coolcoins\provider\mysql\task\MySQLLoadCoinsTask;
use coolcoins\provider\mysql\task\MySQLUpdateCoinsTask;

class MySQLProvider extends DummyProvider {

	/** @var MySQLCredentials */
	private $credentials;

	public function init() {
		$this->credentials = MySQLCredentials::fromArray($this->getPlugin()->settings["database"]);
		$mysqli = $this->credentials->getMysqli();
		if($mysqli->connect_error) {
			$this->getPlugin()->getLogger()->critical("Could not connect to CoolCoins database! Error: " . $mysqli->connect_error);
			$mysqli->close();
			return;
		}
		$mysqli->query("CREATE TABLE IF NOT EXISTS coolcoins (
			username VARCHAR(63) PRIMARY KEY,
			coins INT
		)");
		if(isset($mysqli->error) and $mysqli->error) {
			throw new \RuntimeException($mysqli->error);
		}
		$mysqli->close();
	}

	/**
	 * @return MySQLCredentials
	 */
	public function getCredentials() {
		return $this->credentials;
	}

	public function createSave($player, $coins = 0) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLCreateCoinsSaveTask($this, $player, $coins));
	}

	public function loadSave($player) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLLoadCoinsTask($this, $player));
	}

	public function updateSave($player, $coins = 0) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLUpdateCoinsTask($this, $player, $coins));
	}

	public function deleteSave($player) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLDeleteCoinsSaveTask($this, $player));
	}

}