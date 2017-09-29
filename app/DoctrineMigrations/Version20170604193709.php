<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170604193709 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_1B4F85F2A40BC2D5 ON assistance');
        $this->addSql('ALTER TABLE assistance ADD user_id INT NOT NULL, CHANGE schedule_id session_id INT NOT NULL');
        $this->addSql('ALTER TABLE assistance ADD CONSTRAINT FK_1B4F85F2613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE assistance ADD CONSTRAINT FK_1B4F85F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1B4F85F2613FECDF ON assistance (session_id)');
        $this->addSql('CREATE INDEX IDX_1B4F85F2A76ED395 ON assistance (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assistance DROP FOREIGN KEY FK_1B4F85F2613FECDF');
        $this->addSql('ALTER TABLE assistance DROP FOREIGN KEY FK_1B4F85F2A76ED395');
        $this->addSql('DROP INDEX IDX_1B4F85F2613FECDF ON assistance');
        $this->addSql('DROP INDEX IDX_1B4F85F2A76ED395 ON assistance');
        $this->addSql('ALTER TABLE assistance ADD schedule_id INT NOT NULL, DROP session_id, DROP user_id');
        $this->addSql('CREATE INDEX IDX_1B4F85F2A40BC2D5 ON assistance (schedule_id)');
    }
}
