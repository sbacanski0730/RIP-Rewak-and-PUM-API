<?php

namespace App\Utils;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Query;

use Doctrine\DBAL\Driver\API\ExceptionConverter;

class CollegiumExceptionConverter implements ExceptionConverter {
    public function convert(Exception $exception, ?Query $query): DriverException
    {
        return new DriverException($exception, $query);
    }
}