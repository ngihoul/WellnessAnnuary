<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211230222740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding description property to Provider Entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE provider_customer');
        $this->addSql('ALTER TABLE provider ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE favorite DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE favorite ADD PRIMARY KEY (provider_id, customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE provider_customer (provider_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_C97BE0E09395C3F3 (customer_id), INDEX IDX_C97BE0E0A53A8AA (provider_id), PRIMARY KEY(provider_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE provider_customer ADD CONSTRAINT FK_C97BE0E09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE provider_customer ADD CONSTRAINT FK_C97BE0E0A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE favorite ADD PRIMARY KEY (customer_id, provider_id)');
        $this->addSql('ALTER TABLE provider DROP description');
    }
}
