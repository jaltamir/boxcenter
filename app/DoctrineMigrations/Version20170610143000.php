<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170610143000 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE assistance CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE pass CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE payment CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE refund CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE schedule CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE session CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE updated_datetime updated_datetime DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE assistance CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE pass CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE refund CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE schedule CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE session CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE updated_datetime updated_datetime DATETIME NOT NULL');
    }
}
