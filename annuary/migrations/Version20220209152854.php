<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209152854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding avatar field to Customer Entity & logo field to Provider entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD avatar VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE provider ADD logo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP avatar');
        $this->addSql('ALTER TABLE provider DROP logo');
    }
}
