<?php

namespace coolcoins\provider;

use pocketmine\plugin\Plugin;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

abstract class CoolCoinsRequest extends AsyncTask {

	/**
	 * @param Server $server
	 *
	 * @return Plugin
	 */
	public function getPlugin(Server $server) {
		return $server->getPluginManager()->getPlugin("CoolCoins");
	}
}