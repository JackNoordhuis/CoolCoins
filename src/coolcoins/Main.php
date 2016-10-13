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
use pocketmine\utils\PluginException;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase {

	/** @var Config */
	private $settings;

	/** @var array */
	private $messages = [];

	/** @var DummyProvider */
	protected $provider;

	/** @var EventListener */
	protected $listener;

	/** @var CoinHolder[] */
	public $coinHolders = [];

	/* Resource files */
	const SETTINGS_FILE = "Settings.yml";
	const MESSAGES_FILE = "Messages.yml";

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
		$this->saveResource(self::SETTINGS_FILE);
		$this->settings = new Config($this->getDataFolder() . self::SETTINGS_FILE, Config::YAML);
		$this->saveResource(self::MESSAGES_FILE);
		if(!$this->parseMessage("", (new Config($this->getDataFolder() . self::SETTINGS_FILE, Config::YAML)))) throw new PluginException("Couldn't parse " . self::MESSAGES_FILE . " resource for CoolCoins plugin!");
	}

	/**
	 * @param string $key
	 * @param mixed $message
	 *
	 * @return bool
	 */
	public function parseMessage($key, $message) {
		if(is_string($message)) {
			$this->messages[strtolower($key)] = self::translateColors($message);
		} elseif(is_array($message)) {
			foreach($message as $key => $msg) $this->parseMessage($key, $message);
		} else {
			return false;
		}
		return true;
	}

	/**
	 * @return Config
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * @param $key
	 * @param array $args
	 *
	 * @return string|null
	 */
	public function getMessage($key, array $args = []) {
		return self::translateArguments($this->messages[strtolower($key)], $args);
	}

	/**
	 * Set the provider
	 */
	public function setProvider() {
		switch(strtolower($this->getSettings()->getNested("basic-settings.provider"))) {
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
	 * @param int $coins
	 * @param bool $load
	 */
	public function startCoinHolder(Player $player, $coins = 0, $load = true) {
		if($load) $this->provider->loadSave($player);
		$this->coinHolders[spl_object_hash($player)] = new CoinHolder($player, $coins);
	}

	/**
	 * @param Player $player
	 *
	 * @return CoinHolder|null
	 */
	public function getCoinHolder(Player $player) {
		$hash = spl_object_hash($player);
		return isset($this->coinHolders[$hash]) ? $this->coinHolders[$hash] : null;
	}

	/**
	 * @param Player $player
	 * @param bool $save
	 */
	public function closeCoinHolder(Player $player, $save = true) {
		$hash = spl_object_hash($player);
		if(isset($this->coinHolders[$hash])) {
			$holder = $this->coinHolders[$hash];
			if($save) $this->provider->updateSave($player, $holder->getCoins());
			$holder->close();
			unset($this->coinHolders[$hash]);
		}
	}

	/**
	 * Apply minecraft color codes to a string from our custom ones
	 *
	 * @param string $string
	 * @param string $symbol
	 *
	 * @return string
	 */
	public static function translateColors($string, $symbol = "&") {
		$string = str_replace($symbol . "0", TF::BLACK, $string);
		$string = str_replace($symbol . "1", TF::DARK_BLUE, $string);
		$string = str_replace($symbol . "2", TF::DARK_GREEN, $string);
		$string = str_replace($symbol . "3", TF::DARK_AQUA, $string);
		$string = str_replace($symbol . "4", TF::DARK_RED, $string);
		$string = str_replace($symbol . "5", TF::DARK_PURPLE, $string);
		$string = str_replace($symbol . "6", TF::GOLD, $string);
		$string = str_replace($symbol . "7", TF::GRAY, $string);
		$string = str_replace($symbol . "8", TF::DARK_GRAY, $string);
		$string = str_replace($symbol . "9", TF::BLUE, $string);
		$string = str_replace($symbol . "a", TF::GREEN, $string);
		$string = str_replace($symbol . "b", TF::AQUA, $string);
		$string = str_replace($symbol . "c", TF::RED, $string);
		$string = str_replace($symbol . "d", TF::LIGHT_PURPLE, $string);
		$string = str_replace($symbol . "e", TF::YELLOW, $string);
		$string = str_replace($symbol . "f", TF::WHITE, $string);
		$string = str_replace($symbol . "k", TF::OBFUSCATED, $string);
		$string = str_replace($symbol . "l", TF::BOLD, $string);
		$string = str_replace($symbol . "m", TF::STRIKETHROUGH, $string);
		$string = str_replace($symbol . "n", TF::UNDERLINE, $string);
		$string = str_replace($symbol . "o", TF::ITALIC, $string);
		$string = str_replace($symbol . "r", TF::RESET, $string);
		return $string;
	}

	/**
	 * @param $message
	 * @param array $args
	 *
	 * @return mixed
	 */
	public static function translateArguments($message, $args = []) {
		foreach($args as $key => $data) {
			$message = str_replace("{args" . (string)((int)$key + 1) . "}", $data, $message);
		}
		return $message;
	}

}