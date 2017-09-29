<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170919222345 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assistance DROP FOREIGN KEY FK_1B4F85F2613FECDF');
        $this->addSql('ALTER TABLE assistance CHANGE session_id session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assistance ADD CONSTRAINT FK_1B4F85F2613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assistance DROP FOREIGN KEY FK_1B4F85F2613FECDF');
        $this->addSql('ALTER TABLE assistance CHANGE session_id session_id INT NOT NULL');
        $this->addSql('ALTER TABLE assistance ADD CONSTRAINT FK_1B4F85F2613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
    }
}
