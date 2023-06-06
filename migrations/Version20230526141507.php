<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526141507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //in Version20230524131604, it's doing exactly the same thing of dropping on book table
        //        $this->addSql('ALTER TABLE book DROP title, DROP author, DROP pages, DROP language, DROP rating');
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE book DROP title, DROP author, DROP pages, DROP language, DROP rating');
        //no column 'state' in meetup, the latest change on meetup table is Version20230508014106, if there is a new version in between, uncomment it.
        //$this->addSql('ALTER TABLE meetup DROP state');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE book ADD title VARCHAR(255) NOT NULL, ADD author VARCHAR(255) NOT NULL, ADD pages INT DEFAULT NULL, ADD language VARCHAR(255) DEFAULT NULL, ADD rating DOUBLE PRECISION DEFAULT NULL');
        //$this->addSql('ALTER TABLE meetup ADD state INT NOT NULL');
    }
}
