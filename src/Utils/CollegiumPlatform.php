<?php

namespace App\Utils;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class CollegiumPlatform extends AbstractPlatform
{

    
    public function getCurrentDatabaseExpression(): string {
        return '';
    }
    public function getBooleanTypeDeclarationSQL(array $column)
    {
        return '';
    }
    public function getIntegerTypeDeclarationSQL(array $column)
    {
        return '';
    }
    public function getBigIntTypeDeclarationSQL(array $column)
    {
        return '';
    }
    public function getSmallIntTypeDeclarationSQL(array $column)
    {
        return '';
    }
    protected function _getCommonIntegerTypeDeclarationSQL(array $column)
    {
        return '';
    }
    protected function initializeDoctrineTypeMappings()
    {
    }
    public function getClobTypeDeclarationSQL(array $column)
    {
        return '';
    }
    public function getBlobTypeDeclarationSQL(array $column)
    {
        return '';
    }

    public function supportsSequences()
    {
        return true;
    }

    public function getName()
    {
        return '';
    }

}