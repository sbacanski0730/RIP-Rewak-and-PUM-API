<?php


namespace App\Utils;

use Doctrine\DBAL\Driver;

use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\Driver\Statement as DriverStatement;

class CollegiumConnection implements ServerInfoAwareConnection
{
	private Driver $driver;

	public function __construct($driver) {
		$this->driver = $driver;
	}

	public function prepare(string $sql): DriverStatement
	{
        return new CollegiumStatement();
	}

    public function getServerVersion()
	{
        return `test`;
	}

	public function query(string $sql): CollegiumResult
	{
		return new CollegiumResult();
	}

	public function quote($value, $type = ParameterType::STRING)
	{
		return $value;
	}

    public function exec(string $sql): int
    {
        return 0;
    }
    public function lastInsertId($name = null)
    {
        return false;
    }
    public function beginTransaction()
    {
        return 0;
    }
    public function commit()
    {
        return 0;
    }
    public function rollBack()
    {
        return 0;
    }
}