<?php 


namespace App\Utils;
use Doctrine\DBAL\Driver\Result as DriverResult;

class CollegiumResult implements DriverResult
{
    public function fetchNumeric()
    {
        return [];
    }
    public function fetchAssociative()
    {
        return [];
    }
    public function fetchOne()
    {
        return false;
    }
    public function fetchAllNumeric(): array
    {
        return [];
    }
    public function fetchAllAssociative(): array
    {
        return [];
    }
    public function fetchFirstColumn(): array
    {
        return [];
    }
    public function rowCount(): int
    {
        return 0;
    }
    public function columnCount(): int
    {
        return 0;
    }
    public function free(): void
    {

    }

}