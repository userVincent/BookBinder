<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508004726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE library (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library_book (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_6D2A695CFE2541D7 (library_id), INDEX IDX_6D2A695C16A2B381 (book_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695CFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_book DROP FOREIGN KEY FK_6D2A695CFE2541D7');
        $this->addSql('ALTER TABLE library_book DROP FOREIGN KEY FK_6D2A695C16A2B381');
        $this->addSql('DROP TABLE library');
        $this->addSql('DROP TABLE library_book');
    }
}
