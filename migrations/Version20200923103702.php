<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200923103702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE study_keyword');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE study_keyword (study_id INT NOT NULL, keyword_id INT NOT NULL, INDEX IDX_6F868C59115D4552 (keyword_id), INDEX IDX_6F868C59E7B003E9 (study_id), PRIMARY KEY(study_id, keyword_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE study_keyword ADD CONSTRAINT FK_6F868C59115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE study_keyword ADD CONSTRAINT FK_6F868C59E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
