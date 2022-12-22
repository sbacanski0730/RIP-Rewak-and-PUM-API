<?php


namespace App\Utils;
use Doctrine\DBAL\Driver\Statement as DriverStatement;

class CollegiumStatement implements DriverStatement
{
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        return true;
    }
	public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null)
	{
		return true;
	}
	public function execute($params = null): CollegiumResult
    {
        return new CollegiumResult();
    }
}