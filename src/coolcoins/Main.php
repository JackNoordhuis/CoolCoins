<?php

/**
 * Main.php class
 *
 * Created on 29/05/2016 at 7:39 PM
 *
 * @author Jack
 */

namespace coolcoins;

use coolcoins\command\GetCoinsCommand;
use coolcoins\command\SetCoinsCommand;
use coolcoins\provider\DummyProvider;
use coolcoins\provider\mysql\MySQLProvider;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

	/** @var array */
	public $settings;

	/** @var DummyProvider */
	protected $provider;

	/** @var EventListener */
	protected $listener;

	/** @var CoinHolder[] */
	public $coinHolders = [];

	/**
	 * Enable all the things!
	 */
	public function onEnable() {
		$this->loadConfigs();
		$this->setProvider();
		$this->setListener();
		$this->getServer()->getCommandMap()->registerAll("cc", [
			new GetCoinsCommand($this),
			new SetCoinsCommand($this),
		]);
	}

	/**
	 * Load all the things!
	 */
	public function loadConfigs() {
		$this->saveResource("settings.yml");
		$this->settings = (new Config($this->getDataFolder() . "settings.yml", Config::YAML))->getAll();
	}

	/**
	 * Set the provider
	 */
	public function setProvider() {
		switch(strtolower($this->settings["basic-settings"]["provider"])) {
			default:
				$this->provider = new MySQLProvider($this);
				break;
		}
	}

	/**
	 * Set the listener
	 */
	public function setListener() {
		$this->listener = new EventListener($this);
	}

	/**
	 * @return DummyProvider
	 */
	public function getProvider() {
		return $this->provider;
	}

	/**
	 * @return EventListener
	 */
	public function getListener() {
		return $this->listener;
	}

	/**
	 * @param Player $player
	 * @param $coins
	 */
	public function startCoinHolder(Player $player, $coins = 0) {
		$this->coinHolders[$player->getName()] = new CoinHolder($this, $player, $coins);
	}

	/**
	 * @param Player $player
	 *
	 * @return CoinHolder|null
	 */
	public function getCoinHolder(Player $player) {
		return $this->coinHolders[$player->getName()];
	}

	/**
	 * @param Player $player
	 * @param $coins
	 */
	public function updateCoinHolder(Player $player, $coins) {
		$coinHolder = $this->getCoinHolder($player);
		if($coinHolder instanceof CoinHolder) {
			$coinHolder->updateCoins((int)$coins);
		}
	}

	/**
	 * @param string $player
	 */
	public function closeCoinHolder($player) {
		if(isset($this->coinHolders[$player])) {
			$this->coinHolders[$player]->close();
			unset($this->coinHolders[$player]);
		}
	}

}