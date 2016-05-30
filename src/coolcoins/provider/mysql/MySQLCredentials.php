<?php

/**
 * MySQLCredentials.php Class
 *
 * Created on 29/05/2016 at 7:55 PM
 *
 * @author Jack
 */


namespace coolcoins\provider\mysql;

class MySQLCredentials {

	/** @var string */
	public $host;

	/** @var string */
	public $user;

	/** @var string */
	public $password;

	/** @var string */
	public $name;

	/** @var int */
	public $port;

	/**
	 * Construct a new credentials class from an array
	 *
	 * @param array $array
	 *
	 * @return MySQLCredentials
	 */
	public static function fromArray(array $array) {
		$instance = new self;
		$instance->host = $array["host"];
		$instance->user = $array["user"];
		$instance->password = $array["password"];
		$instance->name = $array["name"];
		$instance->port = $array["port"];
		return $instance;
	}

	/**
	 * Get a new mysqli instance
	 *
	 * @return \mysqli
	 */
	public function getMysqli() {
		return new \mysqli($this->host, $this->user, $this->password, $this->name, $this->port);
	}

}