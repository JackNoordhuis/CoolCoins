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
use coolcoins\provider\mysql\task\MySQLInitRequest;
use coolcoins\provider\mysql\task\MySQLLoadCoinsTask;
use coolcoins\provider\mysql\task\MySQLUpdateCoinsTask;
use pocketmine\Player;

class MySQLProvider extends DummyProvider {

	/** @var MySQLCredentials */
	private $credentials;

	/**
	 * Initial connection with database, check connection and create tables
	 */
	public function init() {
		$this->credentials = MySQLCredentials::fromArray($this->getPlugin()->getSettings()->getNested("database"));
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLInitRequest($this));
	}

	/**
	 * @return MySQLCredentials
	 */
	public function getCredentials() {
		return $this->credentials;
	}

	/**
	 * @param string|Player $player
	 * @param int $coins
	 */
	public function createSave($player, $coins = 0) {
		if($player instanceof Player) $player = $player->getName();
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLCreateCoinsSaveTask($this, $player, $coins));
	}

	/**
	 * @param string|Player $player
	 */
	public function loadSave($player) {
		if($player instanceof Player) $player = $player->getName();
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLLoadCoinsTask($this, $player));
	}

	/**
	 * @param string|Player $player
	 * @param int $coins
	 */
	public function updateSave($player, $coins = 0) {
		if($player instanceof Player) $player = $player->getName();
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLUpdateCoinsTask($this, $player, $coins));
	}

	/**
	 * @param string|Player $player
	 */
	public function deleteSave($player) {
		if($player instanceof Player) $player = $player->getName();
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLDeleteCoinsSaveTask($this, $player));
	}

	/**
	 * @param Player|string $player
	 * @param Player|string $sender
	 */
	public function viewCoins($player, $sender) {
//		if($player instanceof Player) $player = $player->getName();
//		if($sender instanceof Player) $sender = $sender->getName();
//		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLViewCoinsTask($this, $player));
	}

	/**
	 * @param Player|string $player
	 * @param Player|string $sender
	 * @param int $page
	 */
	public function viewTop($player, $sender, $page = 1) {
//		if($player instanceof Player) $player = $player->getName();
//		if($sender instanceof Player) $sender = $sender->getName();
//		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new MySQLViewCoinsTask($this, $player));
	}

}