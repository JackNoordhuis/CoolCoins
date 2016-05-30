<?php

/**
 * MySQLTask.php Class
 *
 * Created on 30/05/2016 at 6:04 PM
 *
 * @author Jack
 */

namespace coolcoins\provider\mysql\task;

use coolcoins\provider\mysql\MySQLCredentials;
use pocketmine\scheduler\AsyncTask;

abstract class MySQLTask extends AsyncTask {

	/** @var MySQLCredentials */
	protected $credentials;

	public function __construct(MySQLCredentials $credentials) {
		$this->credentials = $credentials;
	}

	/**
	 * @return MySQLCredentials
	 */
	public function getCredentials() {
		return $this->credentials;
	}

	/**
	 * @return \mysqli
	 */
	public function getMysqli() {
		return $this->credentials->getMysqli();
	}

}