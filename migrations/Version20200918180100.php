<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918180100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address1 VARCHAR(255) NOT NULL, address2 VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone_number1 VARCHAR(255) NOT NULL, phone_number2 VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, adress1 VARCHAR(255) NOT NULL, address2 VARCHAR(255) DEFAULT NULL, address3 VARCHAR(255) DEFAULT NULL, zip_code INT NOT NULL, city VARCHAR(255) NOT NULL, phone_number1 VARCHAR(255) NOT NULL, phone_number2 VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, city VARCHAR(255) NOT NULL, INDEX IDX_E67F974912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F974912469DE2 FOREIGN KEY (category_id) REFERENCES study_category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study DROP FOREIGN KEY FK_E67F974912469DE2');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE study');
        $this->addSql('DROP TABLE study_category');
        $this->addSql('DROP TABLE user');
    }
}
