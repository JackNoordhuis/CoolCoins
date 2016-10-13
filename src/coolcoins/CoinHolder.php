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

	/** @var string */
	protected $owner;

	/** @var int */
	protected $coins;

	/**
	 * CoinHolder constructor
	 *
	 * @param Player $owner
	 * @param int $coins
	 */
	public function __construct(Player $owner, $coins = 0) {
		$this->owner = $owner->getName();
		$this->coins = $coins;
	}

	/**
	 * @return string
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
	 * @param int $amount
	 *
	 * @return bool
	 */
	public function setCoins($amount = 0) {
		$this->coins = $amount;
		return true;
	}

	/**
	 * @param int $amount
	 *
	 * @return bool
	 */
	public function addCoins($amount = 1) {
		if($amount > 0) {
			$this->coins += $amount;
			return true;
		}
		return false;
	}

	/**
	 * @param int $amount
	 *
	 * @return bool
	 */
	public function takeCoins($amount = 1) {
		if($amount > 0) {
			$this->coins -= $amount;
			return true;
		}
		return false;
	}

	/**
	 * Dump all data and references
	 */
	public function close() {
		unset($this->owner, $this->coins);
	}

}