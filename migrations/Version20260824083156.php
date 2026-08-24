<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260824083156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE exercice_respiration (id INT AUTO_INCREMENT NOT NULL, name_series VARCHAR(100) NOT NULL, time_inspiration INT NOT NULL, time_apnea INT NOT NULL, time_expiration INT NOT NULL, is_predefini TINYINT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_A39BD0D0A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, informations_id INT NOT NULL, INDEX IDX_8C9F361090587D82 (informations_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE informations (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, slug VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, admin_id INT DEFAULT NULL, INDEX IDX_6F966489642B8210 (admin_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE informations_category (informations_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C361BA7D90587D82 (informations_id), INDEX IDX_C361BA7D12469DE2 (category_id), PRIMARY KEY (informations_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, birthdate DATETIME DEFAULT NULL, last_login DATETIME DEFAULT NULL, creation_date DATE NOT NULL, is_active TINYINT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE exercice_respiration ADD CONSTRAINT FK_A39BD0D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361090587D82 FOREIGN KEY (informations_id) REFERENCES informations (id)');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE informations_category ADD CONSTRAINT FK_C361BA7D90587D82 FOREIGN KEY (informations_id) REFERENCES informations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE informations_category ADD CONSTRAINT FK_C361BA7D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice_respiration DROP FOREIGN KEY FK_A39BD0D0A76ED395');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361090587D82');
        $this->addSql('ALTER TABLE informations DROP FOREIGN KEY FK_6F966489642B8210');
        $this->addSql('ALTER TABLE informations_category DROP FOREIGN KEY FK_C361BA7D90587D82');
        $this->addSql('ALTER TABLE informations_category DROP FOREIGN KEY FK_C361BA7D12469DE2');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE exercice_respiration');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE informations');
        $this->addSql('DROP TABLE informations_category');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
