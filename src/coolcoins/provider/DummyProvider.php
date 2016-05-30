<?php

/**
 * DummyProvider.php Class
 *
 * Created on 29/05/2016 at 7:51 PM
 *
 * @author Jack
 */


namespace coolcoins\provider;

use coolcoins\Main;

/**
 * CoolCoins DummyProvider Class
 */
abstract class DummyProvider implements Provider {

	/** @var Main */
	protected $plugin;

	/**
	 * DummyProvider constructor
	 *
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		$this->init();
	}

	/**
	 * Actions to execute when the class is enabled
	 */
	protected abstract function init();

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

}