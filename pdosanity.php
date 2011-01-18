<?php

class PDOSanity extends \PDO {

	private $stmt = NULL;

	public function __construct($dsn, $username=NULL, $password=NULL, $options=array()) {
		parent::__construct($dsn, $username, $password, $options);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function modify($query, $parameters=array()) {
		$this->bind($query, $parameters);
		$exec = $this->stmt->execute();
		if (!$exec) {
			throw new \PDOException('failed to execute query');
		}
		return true;
	}

	public function select($query, $parameters=array()) {
		$this->bind($query, $parameters);
		$exec = $this->stmt->execute();
		if (!$exec) {
			throw new \PDOException('failed to execute query');
		}
		return $this->stmt;
	}

	public function selectOne($query, $parameters=array()) {
		$this->select($query, $parameters);
		return $this->stmt->fetchObject();
	}

	public function stmt() {
		return $this->stmt;
	}

	private function bind($query, $parameters=array()) {
		$this->stmt = $this->prepare($query);
		foreach ($parameters as $parameter => $value) {
			$type = \PDO::PARAM_STR;
			if (is_int($value)) { $type = \PDO::PARAM_INT; }
			if (is_bool($value)) { $type = \PDO::PARAM_BOOL; }
			if (is_null($value)) { $type = \PDO::PARAM_NULL; }
			$this->stmt->bindValue($parameter, $value, $type);
		}

		return $this->stmt;
	}

}
