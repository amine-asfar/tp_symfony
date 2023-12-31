<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208192653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_book (categorie_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_BCCC2466BCF5E72D (categorie_id), INDEX IDX_BCCC246616A2B381 (book_id), PRIMARY KEY(categorie_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_book ADD CONSTRAINT FK_BCCC2466BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_book ADD CONSTRAINT FK_BCCC246616A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_book DROP FOREIGN KEY FK_BCCC2466BCF5E72D');
        $this->addSql('ALTER TABLE categorie_book DROP FOREIGN KEY FK_BCCC246616A2B381');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_book');
    }
}
