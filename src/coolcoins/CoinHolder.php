<?php

/**
 * CoinHolder.php Class
 *
 * Created on 30/05/2016 at 7:49 PM
 *
 * @author Jack
 */


namespace coolcoins;

use pocketmine\Player;

/**
 * Holds a players coins
 */
class CoinHolder {

	/** @var Main */
	protected $plugin;

	/** @var Player */
	protected $owner;

	/** @var int */
	protected $coins;

	/**
	 * CoinHolder constructor
	 *
	 * @param Main $plugin
	 * @param Player $owner
	 * @param int $coins
	 */
	public function __construct(Main $plugin, Player $owner, $coins = 0) {
		$this->plugin = $plugin;
		$this->owner = $owner;
		$this->coins = $coins;
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * @return Player
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @return int
	 */
	public function getCoins() {
		return $this->coins;
	}

	/**
	 * @param int $coins
	 */
	public function updateCoins($coins) {
		$this->coins = $coins;
	}

	/**
	 * Dump all data and reffrences
	 */
	public function close() {
		unset($this->plugin, $this->owner, $this->coins);
	}

}