<?php


namespace App\Utils;

use Doctrine\DBAL\Driver;
use Goutte\Client;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Connection;

class CollegiumDriver implements Driver {
	private $client;
    private $crawler;

    public function __construct()
    {
        $this->client = new Client();

    }

	public function connect(array $params) {
		return new CollegiumConnection($this);
	}

    public function getDatabasePlatform() {
        return new CollegiumPlatform();
    }

    public function getSchemaManager(Connection $conn, AbstractPlatform $platform) {
        return new CollegiumSchemaManager(new Connection([], $this), $this->getDatabasePlatform());
    }

    /**
     * Gets the ExceptionConverter that can be used to convert driver-level exceptions into DBAL exceptions.
     */
    public function getExceptionConverter(): ExceptionConverter
    {
        return new CollegiumExceptionConverter();
    }
}