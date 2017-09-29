<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170402172923 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assistance (id INT AUTO_INCREMENT NOT NULL, created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, schedule_id INT NOT NULL, INDEX IDX_1B4F85F2A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, `desc` VARCHAR(255) NOT NULL, created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, UNIQUE INDEX UNIQ_AC74095A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, nif VARCHAR(9) DEFAULT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, flag_admin TINYINT(1) NOT NULL, created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649ADE62BBB (nif), INDEX IDX_8D93D649E7927C74 (email), INDEX combined_idx (name, surname, email, nif), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, schedule_id INT NOT NULL, start_date_time TIME NOT NULL, active TINYINT(1) NOT NULL, created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, INDEX IDX_D044D5D4A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, start_time TIME NOT NULL, week_day ENUM(\'0\', \'1\', \'2\', \'3\', \'4\', \'5\', \'6\') NOT NULL COMMENT \'(DC2Type:WeekDayEnumType)\', active TINYINT(1) NOT NULL, created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, INDEX IDX_5A3811FB81C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pass_id INT NOT NULL, comments VARCHAR(255) DEFAULT NULL, payment_transaction_token VARCHAR(255) DEFAULT NULL, state INT NOT NULL, payment_confirm_date_time DATETIME DEFAULT NULL, token VARCHAR(255) NOT NULL, net_price DOUBLE PRECISION NOT NULL, vat_price DOUBLE PRECISION NOT NULL, total_price DOUBLE PRECISION NOT NULL, json_payment_response_params LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, INDEX IDX_6D28840DA76ED395 (user_id), INDEX IDX_6D28840DEC545AE5 (pass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pass (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, `desc` VARCHAR(255) NOT NULL, num_sessions INT NOT NULL, created_datetime DATETIME NOT NULL, updated_datetime DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DEC545AE5 FOREIGN KEY (pass_id) REFERENCES pass (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB81C06096');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA76ED395');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4A40BC2D5');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DEC545AE5');
        $this->addSql('DROP TABLE assistance');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE pass');
    }
}
