<?php

/**
 * Provider.php Interface
 *
 * Created on 29/05/2016 at 7:45 PM
 *
 * @author Jack
 */

namespace coolcoins\provider;

/**
 * CoolCoins Provider Interface
 */
interface Provider {

	/**
	 * Create a place to save a players coins
	 *
	 * @param string $player
	 * @param int $coins
	 */
	public function createSave($player, $coins = 0);

	/**
	 * Get a players coins
	 *
	 * @param string $player
	 * @return int
	 */
	public function loadSave($player);

	/**
	 * Update a players coins
	 *
	 * @param string $player
	 * @param int $coins
	 */
	public function updateSave($player, $coins = 0);

	/**
	 * Delete a players save
	 *
	 * @param $player
	 */
	public function deleteSave($player);

}