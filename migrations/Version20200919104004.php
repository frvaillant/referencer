<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200919104004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE study ADD sub_category_id INT NOT NULL, ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F9749F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F9749166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_E67F9749F7BFE87C ON study (sub_category_id)');
        $this->addSql('CREATE INDEX IDX_E67F9749166D1F9C ON study (project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study DROP FOREIGN KEY FK_E67F9749166D1F9C');
        $this->addSql('DROP TABLE project');
        $this->addSql('ALTER TABLE study DROP FOREIGN KEY FK_E67F9749F7BFE87C');
        $this->addSql('DROP INDEX IDX_E67F9749F7BFE87C ON study');
        $this->addSql('DROP INDEX IDX_E67F9749166D1F9C ON study');
        $this->addSql('ALTER TABLE study DROP sub_category_id, DROP project_id');
    }
}
