<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205172656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD hide TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE structure CHANGE logo logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE study ADD show_client TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP hide');
        $this->addSql('ALTER TABLE structure CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE study DROP show_client');
    }
}
