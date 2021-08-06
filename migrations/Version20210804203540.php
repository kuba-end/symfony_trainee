<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804203540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, uploaded_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841859D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_14B7841859D8A214 ON photo (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841859D8A214');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP INDEX IDX_14B7841859D8A214 ON photo');
        $this->addSql('ALTER TABLE photo DROP recipe_id');
    }
}
