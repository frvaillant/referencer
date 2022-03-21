<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216135339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE study_sub_category (study_id INT NOT NULL, sub_category_id INT NOT NULL, INDEX IDX_93C1BEE2E7B003E9 (study_id), INDEX IDX_93C1BEE2F7BFE87C (sub_category_id), PRIMARY KEY(study_id, sub_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE study_sub_category ADD CONSTRAINT FK_93C1BEE2E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE study_sub_category ADD CONSTRAINT FK_93C1BEE2F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE study_sub_category');
    }
}
