<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230105170027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP CONSTRAINT fk_169e6fb948b3eee4');
        $this->addSql('DROP INDEX idx_169e6fb948b3eee4');
        $this->addSql('ALTER TABLE course RENAME COLUMN departament_id TO department_id');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_169E6FB9AE80F5DF ON course (department_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB9AE80F5DF');
        $this->addSql('DROP INDEX IDX_169E6FB9AE80F5DF');
        $this->addSql('ALTER TABLE course RENAME COLUMN department_id TO departament_id');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT fk_169e6fb948b3eee4 FOREIGN KEY (departament_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_169e6fb948b3eee4 ON course (departament_id)');
    }
}
