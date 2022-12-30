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
        switch (true) {
            case $this->matchesSql('SELECT count.*FROM department.*', $sql):
                $result = $this->driver->getDepartmentsCount();
                break;
            case $this->matchesSql('SELECT.*FROM department.*', $sql):
                $result = $this->driver->getDepartments();
                break;
        }

        //string(90) "SELECT d0_.id AS id_0, d0_.name AS name_1 FROM department d0_ ORDER
        // BY d0_.id ASC LIMIT 30"
        // string(50) "SELECT count(d0_.id) AS sclr_0 FROM department d0_"
        // {"@context":"\/api\/contexts\/Department","@id":"\/api\/departments","@type":"hy
        // dra:Collection","hydra:member":[],"hydra:totalItems":0}
        return $result;
		return new CollegiumResult();
	}

    private function matchesSql($pattern, $sql) {
        return preg_match('/'.$pattern.'/', $sql);
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