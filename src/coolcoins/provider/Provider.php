<?php

/**
 * Provider.php Interface
 *
 * Created on 29/05/2016 at 7:45 PM
 *
 * @author Jack
 */

namespace coolcoins\provider;

use pocketmine\Player;

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
	 * Get a players saved coins and store them
	 *
	 * @param string|Player $player
	 */
	public function loadSave($player);

	/**
	 * Update a players coins
	 *
	 * @param string|Player $player
	 * @param int $coins
	 */
	public function updateSave($player, $coins = 0);

	/**
	 * Delete a players save
	 *
	 * @param string|Player
	 */
	public function deleteSave($player);

	/**
	 * @param string|Player $player
	 * @param string|Player $sender
	 */
	public function viewCoins($player, $sender);

	/**
	 * @param string|Player $player
	 * @param string|Player $sender
	 * @param int $page
	 */
	public function viewTop($player, $sender, $page);

}