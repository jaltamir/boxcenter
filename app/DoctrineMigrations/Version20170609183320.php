<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170609183320 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE refund (id INT AUTO_INCREMENT NOT NULL, payment_id INT NOT NULL, status SMALLINT NOT NULL, total NUMERIC(19, 2) NOT NULL, response LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', request LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, INDEX IDX_5B2C14584C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE refund ADD CONSTRAINT FK_5B2C14584C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE payment DROP payment_transaction_token, DROP payment_confirm_date_time, DROP token, CHANGE response response LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE request request LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE refund');
        $this->addSql('ALTER TABLE payment ADD payment_transaction_token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD payment_confirm_date_time DATETIME DEFAULT NULL, ADD token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE response response LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', CHANGE request request LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\'');
    }
}
