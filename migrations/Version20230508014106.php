<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508014106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meetup (id INT AUTO_INCREMENT NOT NULL, person1_id INT NOT NULL, person2_id INT NOT NULL, library_id INT NOT NULL, date DATE NOT NULL, time TIME NOT NULL, INDEX IDX_9377E283EF5821B (person1_id), INDEX IDX_9377E282C402DF5 (person2_id), INDEX IDX_9377E28FE2541D7 (library_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meetup ADD CONSTRAINT FK_9377E283EF5821B FOREIGN KEY (person1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meetup ADD CONSTRAINT FK_9377E282C402DF5 FOREIGN KEY (person2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meetup ADD CONSTRAINT FK_9377E28FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meetup DROP FOREIGN KEY FK_9377E283EF5821B');
        $this->addSql('ALTER TABLE meetup DROP FOREIGN KEY FK_9377E282C402DF5');
        $this->addSql('ALTER TABLE meetup DROP FOREIGN KEY FK_9377E28FE2541D7');
        $this->addSql('DROP TABLE meetup');
    }
}
