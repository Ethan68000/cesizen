<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260321131251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice_respiration ADD is_predefini TINYINT NOT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice_respiration ADD CONSTRAINT FK_A39BD0D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A39BD0D0A76ED395 ON exercice_respiration (user_id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361090587D82 FOREIGN KEY (informations_id) REFERENCES informations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice_respiration DROP FOREIGN KEY FK_A39BD0D0A76ED395');
        $this->addSql('DROP INDEX IDX_A39BD0D0A76ED395 ON exercice_respiration');
        $this->addSql('ALTER TABLE exercice_respiration DROP is_predefini, DROP user_id');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361090587D82');
    }
}
