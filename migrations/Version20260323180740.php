<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323180740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE informations_category (informations_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C361BA7D90587D82 (informations_id), INDEX IDX_C361BA7D12469DE2 (category_id), PRIMARY KEY (informations_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE informations_category ADD CONSTRAINT FK_C361BA7D90587D82 FOREIGN KEY (informations_id) REFERENCES informations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE informations_category ADD CONSTRAINT FK_C361BA7D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_respiration ADD CONSTRAINT FK_A39BD0D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361090587D82 FOREIGN KEY (informations_id) REFERENCES informations (id)');
        $this->addSql('ALTER TABLE informations ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6F966489642B8210 ON informations (admin_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informations_category DROP FOREIGN KEY FK_C361BA7D90587D82');
        $this->addSql('ALTER TABLE informations_category DROP FOREIGN KEY FK_C361BA7D12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE informations_category');
        $this->addSql('ALTER TABLE exercice_respiration DROP FOREIGN KEY FK_A39BD0D0A76ED395');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361090587D82');
        $this->addSql('ALTER TABLE informations DROP FOREIGN KEY FK_6F966489642B8210');
        $this->addSql('DROP INDEX IDX_6F966489642B8210 ON informations');
        $this->addSql('ALTER TABLE informations DROP admin_id');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
    }
}
