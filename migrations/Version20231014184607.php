<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014184607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tax_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tax_rate (id INT NOT NULL, rate NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE country ADD tax_rate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966FDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5373C966FDD13F95 ON country (tax_rate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE country DROP CONSTRAINT FK_5373C966FDD13F95');
        $this->addSql('DROP SEQUENCE tax_rate_id_seq CASCADE');
        $this->addSql('DROP TABLE tax_rate');
        $this->addSql('DROP INDEX IDX_5373C966FDD13F95');
        $this->addSql('ALTER TABLE country DROP tax_rate_id');
    }
}
