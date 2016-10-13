<?php

/**
 * EventListener.php Class
 *
 * Created on 30/05/2016 at 8:33 PM
 *
 * @author Jack
 */


namespace coolcoins;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

	/** @var Main */
	protected $plugin;

	/**
	 * EventListener constructor
	 *
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * Load all the coins!
	 *
	 * @param PlayerLoginEvent $event
	 *
	 * @priority MONITOR
	 */
	public function onJoin(PlayerLoginEvent $event) {
		$player = $event->getPlayer();
		$this->plugin->startCoinHolder($player, 0, true);
	}

	/**
	 * Load all the coins!
	 *
	 * @param PlayerQuitEvent $event
	 *
	 * @priority MONITOR
	 */
	public function onQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$this->getPlugin()->closeCoinHolder($player);
	}

}