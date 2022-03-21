<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918184537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F974919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_E67F974919EB6921 ON study (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study DROP FOREIGN KEY FK_E67F974919EB6921');
        $this->addSql('DROP INDEX IDX_E67F974919EB6921 ON study');
        $this->addSql('ALTER TABLE study DROP client_id');
    }
}
