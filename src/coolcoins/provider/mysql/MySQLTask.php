<?php

/**
 * MySQLTask.php Class
 *
 * Created on 30/05/2016 at 6:04 PM
 *
 * @author Jack
 */

namespace coolcoins\provider\mysql;

use coolcoins\provider\CoolCoinsRequest;
use coolcoins\provider\mysql\MySQLCredentials;

abstract class MySQLTask extends CoolCoinsRequest {

	/** @var MySQLCredentials */
	protected $credentials;

	/** States */
	const CONNECTION_ERROR = "state.connection.error";
	const MYSQLI_ERROR = "state.mysqli.error";
	const NO_DATA = "state.no.data";
	const WRONG_FORMAT = "state.wrong.format";
	const NO_CHANGE = "state.no.change";
	const SUCCESS = "state.success";

	/* The key used to store a mysqli instance onto the thread */
	const MYSQLI_KEY = "coolcoins.mysqli.database";

	public function __construct(MySQLCredentials $credentials) {
		$this->credentials = $credentials;
	}

	/**
	 * @return \mysqli
	 */
	public function getMysqli() {
		$mysqli = $this->getFromThreadStore(self::MYSQLI_KEY);
		if($mysqli !== null) {
			return $mysqli;
		}
		$mysqli = $this->credentials->getMysqli();
		$this->saveToThreadStore(self::MYSQLI_KEY, $mysqli);

		return $mysqli;
	}

	/**
	 * @param \mysqli $mysqli
	 *
	 * @return bool
	 */
	public function checkConnection(\mysqli $mysqli) {
		if($mysqli->connect_error) {
			$this->setResult([self::CONNECTION_ERROR, $mysqli->connect_error]);
			return true;
		}
		return false;
	}

	/**
	 * @param \mysqli_stmt $stmt
	 *
	 * @return bool
	 */
	public function checkError(\mysqli_stmt $stmt) {
		if($stmt->error) {
			$this->setResult([self::MYSQLI_ERROR, $stmt->error]);
			return true;
		}
		return false;
	}

	/**
	 * @param \mysqli_stmt $stmt
	 *
	 * @return bool
	 */
	public function checkAffectedRows(\mysqli_stmt $stmt) {
		if($stmt->affected_rows < 0) {
			$this->setResult([self::NO_CHANGE]);
			return true;
		}
		return false;
	}

	/**
	 * @param mixed $data
	 *
	 * @return bool
	 */
	public function checkResult($data) {
		if(!is_array($data)) {
			$this->setResult([self::NO_DATA]);
			return true;
		}
		return false;
	}

}