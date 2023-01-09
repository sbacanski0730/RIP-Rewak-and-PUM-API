<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230124835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT fk_6dc044c5f9295384');
        $this->addSql('DROP INDEX idx_6dc044c5f9295384');
        $this->addSql('ALTER TABLE "group" RENAME COLUMN courses_id TO course_id');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C5591CC992 ON "group" (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5591CC992');
        $this->addSql('DROP INDEX IDX_6DC044C5591CC992');
        $this->addSql('ALTER TABLE "group" RENAME COLUMN course_id TO courses_id');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT fk_6dc044c5f9295384 FOREIGN KEY (courses_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6dc044c5f9295384 ON "group" (courses_id)');
    }
}
