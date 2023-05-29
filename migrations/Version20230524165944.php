<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524165944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_favorite_books (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_9716DEEFA76ED395 (user_id), INDEX IDX_9716DEEF16A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_favorite_books ADD CONSTRAINT FK_9716DEEFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_books ADD CONSTRAINT FK_9716DEEF16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_favorite_books DROP FOREIGN KEY FK_9716DEEFA76ED395');
        $this->addSql('ALTER TABLE user_favorite_books DROP FOREIGN KEY FK_9716DEEF16A2B381');
        $this->addSql('DROP TABLE user_favorite_books');
    }
}
