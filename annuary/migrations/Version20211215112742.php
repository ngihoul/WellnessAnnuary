<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215112742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating Entities & Relations except those for address management';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, customer_id INT NOT NULL, title VARCHAR(120) NOT NULL, content VARCHAR(255) NOT NULL, rating INT NOT NULL, published_at DATETIME NOT NULL, INDEX IDX_9474526CA53A8AA (provider_id), INDEX IDX_9474526C9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, newsletter TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_81398E09A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite (customer_id INT NOT NULL, provider_id INT NOT NULL, INDEX IDX_68C58ED99395C3F3 (customer_id), INDEX IDX_68C58ED9A53A8AA (provider_id), PRIMARY KEY(customer_id, provider_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, service_category_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, provider_id INT DEFAULT NULL, ordering INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_C53D045FDEDCBB4E (service_category_id), INDEX IDX_C53D045F9395C3F3 (customer_id), INDEX IDX_C53D045FA53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internship (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, name VARCHAR(120) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, additional_information LONGTEXT DEFAULT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, displayed_from DATE NOT NULL, displayed_until DATE NOT NULL, INDEX IDX_10D1B00CA53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, published_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, pdfdocument VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, service_category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, pdfdocument VARCHAR(255) DEFAULT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, displayed_from DATE NOT NULL, displayed_until DATE NOT NULL, INDEX IDX_C11D7DD1A53A8AA (provider_id), INDEX IDX_C11D7DD1DEDCBB4E (service_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(25) NOT NULL, vtanumber VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_92C4739CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider_category (provider_id INT NOT NULL, service_category_id INT NOT NULL, INDEX IDX_4E0E7728A53A8AA (provider_id), INDEX IDX_4E0E7728DEDCBB4E (service_category_id), PRIMARY KEY(provider_id, service_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider_customer (provider_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_C97BE0E0A53A8AA (provider_id), INDEX IDX_C97BE0E09395C3F3 (customer_id), PRIMARY KEY(provider_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, comment_id INT NOT NULL, description VARCHAR(255) NOT NULL, reported_at DATETIME NOT NULL, INDEX IDX_C42F77849395C3F3 (customer_id), INDEX IDX_C42F7784F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, description LONGTEXT NOT NULL, highlighted TINYINT(1) NOT NULL, validated TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, address_number VARCHAR(10) NOT NULL, address_street VARCHAR(255) NOT NULL, registered_on DATETIME NOT NULL, unsuccessful_attempts INT DEFAULT NULL, banned TINYINT(1) NOT NULL, registration_confirmed TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FDEDCBB4E FOREIGN KEY (service_category_id) REFERENCES service_category (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE internship ADD CONSTRAINT FK_10D1B00CA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1DEDCBB4E FOREIGN KEY (service_category_id) REFERENCES service_category (id)');
        $this->addSql('ALTER TABLE provider ADD CONSTRAINT FK_92C4739CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE provider_category ADD CONSTRAINT FK_4E0E7728A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE provider_category ADD CONSTRAINT FK_4E0E7728DEDCBB4E FOREIGN KEY (service_category_id) REFERENCES service_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE provider_customer ADD CONSTRAINT FK_C97BE0E0A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE provider_customer ADD CONSTRAINT FK_C97BE0E09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77849395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784F8697D13');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9395C3F3');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED99395C3F3');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F9395C3F3');
        $this->addSql('ALTER TABLE provider_customer DROP FOREIGN KEY FK_C97BE0E09395C3F3');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77849395C3F3');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA53A8AA');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A53A8AA');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA53A8AA');
        $this->addSql('ALTER TABLE internship DROP FOREIGN KEY FK_10D1B00CA53A8AA');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1A53A8AA');
        $this->addSql('ALTER TABLE provider_category DROP FOREIGN KEY FK_4E0E7728A53A8AA');
        $this->addSql('ALTER TABLE provider_customer DROP FOREIGN KEY FK_C97BE0E0A53A8AA');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FDEDCBB4E');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1DEDCBB4E');
        $this->addSql('ALTER TABLE provider_category DROP FOREIGN KEY FK_4E0E7728DEDCBB4E');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395');
        $this->addSql('ALTER TABLE provider DROP FOREIGN KEY FK_92C4739CA76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE internship');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE provider_category');
        $this->addSql('DROP TABLE provider_customer');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('DROP TABLE user');
    }
}
