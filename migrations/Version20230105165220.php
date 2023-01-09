<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230105165220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD departament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB948B3EEE4 FOREIGN KEY (departament_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_169E6FB948B3EEE4 ON course (departament_id)');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT fk_3bae0aa7ae80f5df');
        $this->addSql('DROP INDEX idx_3bae0aa7ae80f5df');
        $this->addSql('ALTER TABLE event DROP department_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB948B3EEE4');
        $this->addSql('DROP INDEX IDX_169E6FB948B3EEE4');
        $this->addSql('ALTER TABLE course DROP departament_id');
        $this->addSql('ALTER TABLE event ADD department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_3bae0aa7ae80f5df FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3bae0aa7ae80f5df ON event (department_id)');
    }
}
