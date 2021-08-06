<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806133938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe ADD photo_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137C51599E0 FOREIGN KEY (photo_id_id) REFERENCES photo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA88B137C51599E0 ON recipe (photo_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137C51599E0');
        $this->addSql('DROP INDEX UNIQ_DA88B137C51599E0 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP photo_id_id');
    }
}
