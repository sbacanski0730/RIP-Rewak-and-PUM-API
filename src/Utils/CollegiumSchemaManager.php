<?php

namespace App\Utils;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\StringType;

class CollegiumSchemaManager extends AbstractSchemaManager
{
	public function _getPortableTableColumnDefinition($tableColumn) {
		return new Column('a', new StringType , []);
	}
}