<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221229131302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE department_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE worker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE building (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE course (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE department (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE department_worker (department_id INT NOT NULL, worker_id INT NOT NULL, PRIMARY KEY(department_id, worker_id))');
        $this->addSql('CREATE INDEX IDX_8BA84092AE80F5DF ON department_worker (department_id)');
        $this->addSql('CREATE INDEX IDX_8BA840926B20BA36 ON department_worker (worker_id)');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, room_id INT DEFAULT NULL, department_id INT DEFAULT NULL, worker_id INT DEFAULT NULL, group_id VARCHAR(255) DEFAULT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, subject VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA754177093 ON event (room_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7AE80F5DF ON event (department_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA76B20BA36 ON event (worker_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7FE54D947 ON event (group_id)');
        $this->addSql('CREATE TABLE "group" (id VARCHAR(255) NOT NULL, courses_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DC044C5F9295384 ON "group" (courses_id)');
        $this->addSql('CREATE TABLE room (id INT NOT NULL, building_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_729F519B4D2A7E12 ON room (building_id)');
        $this->addSql('CREATE TABLE worker (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE worker_department (worker_id INT NOT NULL, department_id INT NOT NULL, PRIMARY KEY(worker_id, department_id))');
        $this->addSql('CREATE INDEX IDX_4921A0F96B20BA36 ON worker_department (worker_id)');
        $this->addSql('CREATE INDEX IDX_4921A0F9AE80F5DF ON worker_department (department_id)');
        $this->addSql('ALTER TABLE department_worker ADD CONSTRAINT FK_8BA84092AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE department_worker ADD CONSTRAINT FK_8BA840926B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA754177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5F9295384 FOREIGN KEY (courses_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE worker_department ADD CONSTRAINT FK_4921A0F96B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE worker_department ADD CONSTRAINT FK_4921A0F9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE department_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE worker_id_seq CASCADE');
        $this->addSql('ALTER TABLE department_worker DROP CONSTRAINT FK_8BA84092AE80F5DF');
        $this->addSql('ALTER TABLE department_worker DROP CONSTRAINT FK_8BA840926B20BA36');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA754177093');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7AE80F5DF');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA76B20BA36');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7FE54D947');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5F9295384');
        $this->addSql('ALTER TABLE room DROP CONSTRAINT FK_729F519B4D2A7E12');
        $this->addSql('ALTER TABLE worker_department DROP CONSTRAINT FK_4921A0F96B20BA36');
        $this->addSql('ALTER TABLE worker_department DROP CONSTRAINT FK_4921A0F9AE80F5DF');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE department_worker');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE worker');
        $this->addSql('DROP TABLE worker_department');
    }
}
