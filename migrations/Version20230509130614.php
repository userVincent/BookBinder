<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509130614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library CHANGE name name VARCHAR(255) DEFAULT NULL, ADD street_name VARCHAR(255), ADD house_number VARCHAR(255), ADD postal_code VARCHAR(255), ADD type VARCHAR(255), ADD year VARCHAR(255), ADD town VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library CHANGE name name VARCHAR(255) NOT NULL, DROP street_name, DROP house_number, DROP postal_code, DROP type, DROP year, DROP town');

    }
}
