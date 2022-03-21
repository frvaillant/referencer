<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204195033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study ADD city_id INT DEFAULT NULL, DROP city');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F97498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_E67F97498BAC62AF ON study (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study DROP FOREIGN KEY FK_E67F97498BAC62AF');
        $this->addSql('DROP INDEX IDX_E67F97498BAC62AF ON study');
        $this->addSql('ALTER TABLE study ADD city VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP city_id');
    }
}
