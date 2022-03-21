<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209133453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study ADD project_manager_id INT DEFAULT NULL, ADD hide_city TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F974960984F51 FOREIGN KEY (project_manager_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_E67F974960984F51 ON study (project_manager_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study DROP FOREIGN KEY FK_E67F974960984F51');
        $this->addSql('DROP INDEX IDX_E67F974960984F51 ON study');
        $this->addSql('ALTER TABLE study DROP project_manager_id, DROP hide_city');
    }
}
